<?php declare(strict_types = 1);

namespace App\Service;

use App\DTO\JobDTO;
use App\DTO\PaginationDTO;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecruitisApiService
{
    private const API_BASE_URL = 'https://app.recruitis.io/api2/';
    private const API_TOKEN = ''; // insert valid access token

    public function __construct(private readonly HttpClientInterface $client)
    {
    }

    /**
     * @param array<string, mixed> $queryParams
     * @return array<string, mixed>
     */
    public function requestGet(string $endpoint, array $queryParams = []): array
    {
        $response = $this->client->request('GET', self::API_BASE_URL . $endpoint, [
            'query' => $queryParams,
            'headers' => [
                'Authorization' => 'Bearer ' . self::API_TOKEN,
            ],
        ]);
        return $response->toArray();
    }

    /**
     * @return array{jobs: \App\DTO\JobDTO[], pagination: \App\DTO\PaginationDTO}
     */
    public function fetchJobs(int $page = 1, int $perPage = 10): array
    {
        $response = $this->requestGet('jobs', ['page' => $page, 'per_page' => $perPage]);

        $jobs = array_map(fn($job) => new JobDTO($job['title'], $job['description']), $response['payload'] ?? []);
        $pagination = new PaginationDTO(
            $response['meta']['entries_from'] ?? 0,
            $response['meta']['entries_to'] ?? 0,
            $response['meta']['entries_total'] ?? 0,
            $response['meta']['entries_sum'] ?? 0
        );

        return [
            'jobs' => $jobs,
            'pagination' => $pagination,
        ];
    }
}