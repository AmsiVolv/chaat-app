<?php
declare(strict_types=1);

namespace App\Controller;

use InvalidArgumentException;
use stdClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait CheckRequestDataTrait
 * @package App\Controller
 */
trait CheckRequestDataTrait
{
    /**
     * @param array $requiredProps
     * @param array $oneOfRequiredProps
     * @param Request $request
     * @return stdClass
     */
    private function checkData(array $requiredProps, array $oneOfRequiredProps, Request $request): stdClass
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            return $this->checkPOST($requiredProps, $oneOfRequiredProps, $request);
        }

        if ($request->isMethod(Request::METHOD_GET)) {
            return $this->checkGET($requiredProps, $oneOfRequiredProps, $request);
        }

        if ($request->isMethod(Request::METHOD_PUT)) {
            // ...
        }

        if ($request->isMethod(Request::METHOD_PATCH)) {
            // ...
        }

        return (object) [];
    }

    /**
     * @param array $requiredProps
     * @param array $oneOfRequiredProps
     * @param Request $request
     * @return stdClass
     */
    private function checkPOST(array $requiredProps, array $oneOfRequiredProps, Request $request): stdClass
    {
        $requestContent = $request->getContent();
        $data = json_decode($requestContent, true);

        $this->checkRequired($requiredProps, $data);
        $this->checkOneOfRequired($oneOfRequiredProps, $data);

        return (object) $data;
    }

    /**
     * @param array $requiredProps
     * @param array $oneOfRequiredProps
     * @param Request $request
     * @return stdClass
     */
    private function checkGET(array $requiredProps, array $oneOfRequiredProps, Request $request): stdClass
    {
        $data = $request->query->all();
        $data = $this->unsetEmptyProps($data);

        $this->checkRequired($requiredProps, $data);
        $this->checkOneOfRequired($oneOfRequiredProps, $data);

        return (object) $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function unsetEmptyProps(array $data): array
    {
        return array_filter($data, fn ($value) => $value !== null && $value !== '');
    }

    /**
     * @param array $props
     * @param array $data
     */
    private function checkRequired(array $props, array $data): void
    {
        if ($props === []) {
            return;
        }

        foreach ($props as $prop) {
            if (!array_key_exists($prop, $data)) {
                throw new InvalidArgumentException(sprintf('Missing required property - %s.', $prop));
            }
        }
    }

    /**
     * @param array $props
     * @param array $data
     */
    private function checkOneOfRequired(array $props, array $data): void
    {
        if ($props === []) {
            return;
        }

        foreach ($props as $prop) {
            if (array_key_exists($prop, $data)) {
                return;
            }
        }

        throw new InvalidArgumentException(sprintf('At least one of the %s must be set.', implode(', ', $props)));
    }
}
