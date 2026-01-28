<?php

namespace App\Services;

use Symfony\Component\Yaml\Yaml;

class CatalogService
{
    protected string $catalogPath;

    public function __construct()
    {
        // Get project root (two levels up from app/Services/)
        $this->catalogPath = dirname(__DIR__, 2) . '/../catalog.yaml';
    }

    /**
     * Get all catalog entries with all details
     *
     * @return array
     */
    public function getEntries(): array
    {
        if (!file_exists($this->catalogPath)) {
            return [];
        }

        $yamlContent = file_get_contents($this->catalogPath);
        $catalog = Yaml::parse($yamlContent);

        if (!is_array($catalog)) {
            return [];
        }

        $entries = [];

        foreach ($catalog as $key => $entry) {
            $trmnlp = $entry['trmnlp'] ?? [];
            $byos = $entry['byos'] ?? [];
            $byosLaravel = $byos['byos_laravel'] ?? [];
            $author = $entry['author'] ?? [];
            $funding = $entry['funding'] ?? [];
            $authorBio = $entry['author_bio'] ?? [];

            $entries[] = [
                'key' => $key,
                'id' => $trmnlp['id'] ?? null,
                'name' => $entry['name'] ?? '',
                'zip_url' => $trmnlp['zip_url'] ?? '',
                'repo' => $trmnlp['repo'] ?? '',
                'zip_entry_path' => $trmnlp['zip_entry_path'] ?? null,
                'version' => $trmnlp['version'] ?? null,
                'logo_url' => $entry['logo_url'] ?? null,
                'screenshot_url' => $entry['screenshot_url'] ?? null,
                'license' => $entry['license'] ?? null,
                'author' => [
                    'github' => $author['github'] ?? '',
                ],
                'author_bio' => [
                    'category' => $authorBio['category'] ?? null,
                    'description' => $authorBio['description'] ?? null,
                    'homepage' => $authorBio['homepage'] ?? null,
                    'learn_more_url' => $authorBio['learn_more_url'] ?? null,
                    'email_address' => $authorBio['email_address'] ?? null,
                    'github_url' => $authorBio['github_url'] ?? null,
                ],
                'byos_laravel' => [
                    'compatibility' => $byosLaravel['compatibility'] ?? false,
                    'compatibility_note' => $byosLaravel['compatibility_note'] ?? null,
                    'min_version' => $byosLaravel['min_version'] ?? null,
                    'installation_instructions' => $byosLaravel['installation_instructions'] ?? null,
                ],
                'funding' => $funding,
                'trmnlp' => $trmnlp,
                'byos' => $byos,
            ];
        }

        return $entries;
    }

    /**
     * Get a single catalog entry by key
     *
     * @param string $key
     * @return array|null
     */
    public function getEntry(string $key): ?array
    {
        $entries = $this->getEntries();
        
        foreach ($entries as $entry) {
            if ($entry['key'] === $key) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * Get all unique categories with counts
     *
     * @return array
     */
    public function getCategories(): array
    {
        $entries = $this->getEntries();
        $categories = [];
        $categoryCounts = [];

        foreach ($entries as $entry) {
            $category = $entry['author_bio']['category'] ?? null;
            if ($category) {
                $cats = array_map('trim', explode(',', $category));
                foreach ($cats as $cat) {
                    if ($cat) {
                        if (!in_array($cat, $categories)) {
                            $categories[] = $cat;
                        }
                        if (!isset($categoryCounts[$cat])) {
                            $categoryCounts[$cat] = 0;
                        }
                        $categoryCounts[$cat]++;
                    }
                }
            }
        }

        sort($categories);

        return [
            'categories' => $categories,
            'counts' => $categoryCounts,
        ];
    }
}
