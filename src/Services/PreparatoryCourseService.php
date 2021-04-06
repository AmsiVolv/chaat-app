<?php
declare(strict_types=1);

namespace App\Services;

use App\Repository\PreparatoryCoursesRepository;

/**
 * Class PreparatoryCourseService
 * @package App\Services
 */
class PreparatoryCourseService
{
    private PreparatoryCoursesRepository $preparatoryCourseRepository;

    public function __construct(PreparatoryCoursesRepository $preparatoryCourseRepository)
    {
        $this->preparatoryCourseRepository = $preparatoryCourseRepository;
    }

    public function getAll(): array
    {
        return $this->preparatoryCourseRepository->getAll();
    }
}
