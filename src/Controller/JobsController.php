<?php declare(strict_types = 1);

namespace App\Controller;

use App\Facade\JobsFacade;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobsController extends AbstractController
{

    public function __construct(private readonly JobsFacade $jobsFacade)
    {
    }

    #[Route("/jobs", name: "jobs")]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('per_page', 10);

        $jobs = [];
        $pagination = [];

        try {
            $data = $this->jobsFacade->getJobs($page, $perPage);
            $jobs = $data['jobs'];
            $pagination = $data['pagination'];
        } catch (\Exception|InvalidArgumentException) {
            $this->addFlash('error', 'Nastala chyba při získávání záznamů. Zkuste to prosím později.');
        }

        return $this->render('jobs.html.twig', [
            'jobs' => $jobs,
            'pagination' => $pagination,
            'page' => $page,
            'per_page' => $perPage,
        ]);
    }
}