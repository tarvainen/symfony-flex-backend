<?php
declare(strict_types = 1);
/**
 * /src/Rest/Traits/Methods/DeleteMethod.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Rest\Traits\Methods;

use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Trait DeleteMethod
 *
 * @package App\Rest\Traits\Methods
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
trait DeleteMethod
{
    // Traits
    use AbstractGenericMethods;

    /**
     * Generic 'deleteMethod' method for REST resources.
     *
     * @param Request       $request
     * @param string        $id
     * @param string[]|null $allowedHttpMethods
     *
     * @return Response
     *
     * @throws LogicException
     * @throws Throwable
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    public function deleteMethod(Request $request, string $id, ?array $allowedHttpMethods = null): Response
    {
        $allowedHttpMethods = $allowedHttpMethods ?? ['DELETE'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        // Get current resource service
        $resource = $this->getResource();

        try {
            // Fetch data from database
            return $this
                ->getResponseHandler()
                ->createResponse($request, $resource->delete($id), $resource);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception, $id);
        }
    }
}
