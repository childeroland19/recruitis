<?php declare(strict_types = 1);

namespace App\Tests\Service;

use App\Service\RecruitisApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RecruitisApiServiceTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private RecruitisApiService $service;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->service = new RecruitisApiService($this->httpClient);
    }

    public function testRequestSuccess(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn([
            'payload' => [
                ['title' => 'Test Job', 'description' => 'Test Description']
            ],
            'meta' => [
                'entries_from' => 1,
                'entries_to' => 10,
                'entries_total' => 1,
                'entries_sum' => 1
            ]
        ]);

        $this->httpClient->method('request')->willReturn($response);

        $result = $this->service->requestGet('jobs', ['page' => 1, 'per_page' => 10]);

        $this->assertSame('Test Job', $result['payload'][0]['title']);
        $this->assertSame('Test Description', $result['payload'][0]['description']);
        $this->assertSame(1, $result['meta']['entries_total']);
        $this->assertSame(1, $result['meta']['entries_from']);
        $this->assertSame(10, $result['meta']['entries_to']);
    }

    public function testRequestFailure(): void
    {
        $this->httpClient->method('request')->willThrowException(new \Exception());

        $this->expectException(\Exception::class);

        $this->service->requestGet('jobs', ['page' => 1, 'per_page' => 10]);
    }

    public function testFetchJobs(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')->willReturn([
            'payload' => [
                ['title' => 'Test Job', 'description' => 'Test Description']
            ],
            'meta' => [
                'entries_from' => 1,
                'entries_to' => 10,
                'entries_total' => 1,
                'entries_sum' => 1
            ]
        ]);

        $this->httpClient->method('request')->willReturn($response);

        $result = $this->service->fetchJobs(1, 10);

        $this->assertSame('Test Job', $result['jobs'][0]->getTitle());
        $this->assertSame('Test Description', $result['jobs'][0]->getDescription());
        $this->assertSame(1, $result['pagination']->getEntriesTotal());
        $this->assertSame(1, $result['pagination']->getEntriesFrom());
        $this->assertSame(10, $result['pagination']->getEntriesTo());
    }
}