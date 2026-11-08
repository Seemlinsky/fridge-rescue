<?php

namespace App\Recommendation;

use RuntimeException;

/**
 * ML-strategy: roept ml/recommend.py aan, dat TF-IDF (TfidfVectorizer) en
 * NearestNeighbors met cosine distance uit scikit-learn gebruikt.
 *
 * Laravel en Python praten via JSON over stdin/stdout.
 */
class KnnTfidfStrategy implements RecipeRecommendationStrategy
{
    public function __construct(
        private readonly string $pythonBinary,
        private readonly string $scriptPath,
        private readonly int $timeoutSeconds = 15,
    ) {}

    public function recommend(array $fridge, array $recipes, int $limit = 5): array
    {
        if ($fridge === [] || $recipes === []) {
            return [];
        }

        $payload = json_encode([
            'fridge' => array_values($fridge),
            'recipes' => array_values($recipes),
            'limit' => $limit,
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        $output = $this->runPython($payload);

        $results = json_decode($output, true);

        if (! is_array($results)) {
            throw new RuntimeException('recommend.py gaf geen geldige JSON terug: '.$output);
        }

        return $results;
    }

    public function label(): string
    {
        return 'ML: TF-IDF + NearestNeighbors (cosine)';
    }

    private function runPython(string $input): string
    {
        $command = [$this->pythonBinary, $this->scriptPath];

        $process = proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ], $pipes);

        if (! is_resource($process)) {
            throw new RuntimeException("Kon Python niet starten ({$this->pythonBinary}).");
        }

        fwrite($pipes[0], $input);
        fclose($pipes[0]);

        stream_set_timeout($pipes[1], $this->timeoutSeconds);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            throw new RuntimeException('recommend.py faalde: '.trim((string) $stderr));
        }

        return (string) $stdout;
    }
}
