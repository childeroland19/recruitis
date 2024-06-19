<?php declare(strict_types = 1);

namespace App\Facade;

use App\Service\RecruitisApiService;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class JobsFacade
{
    public function __construct(
        private readonly CacheInterface      $cache,
        private readonly RecruitisApiService $apiService
    )
    {
    }


    /**
     * @return array{jobs: \App\DTO\JobDTO[], pagination: \App\DTO\PaginationDTO}
     */
    public function getJobs(int $page = 1, int $perPage = 10): array
    {
        $cacheKey = 'jobs_page_' . $page . '_perPage_' . $perPage;

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($page, $perPage) {
            $item->expiresAfter(3600);

            return $this->apiService->fetchJobs($page, $perPage);
        });
    }
}