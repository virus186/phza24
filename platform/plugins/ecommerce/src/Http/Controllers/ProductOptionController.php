<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Forms\GlobalOptionForm;
use Botble\Ecommerce\Http\Requests\ProductOptionRequest;
use Botble\Ecommerce\Repositories\Interfaces\GlobalOptionInterface;
use Botble\Ecommerce\Tables\GlobalOptionTable;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ProductOptionController extends BaseController
{
    /**
     * @var GlobalOptionInterface
     */
    protected $globalOptionRepository;

    /**
     * @param GlobalOptionInterface $globalOptionRepository
     */
    public function __construct(GlobalOptionInterface $globalOptionRepository)
    {
        $this->globalOptionRepository = $globalOptionRepository;
    }

    /**
     * @return View|JsonResponse
     * @throws Throwable
     */
    public function index(GlobalOptionTable $table)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-option.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::product-option.create'));

        return $formBuilder->create(GlobalOptionForm::class)->renderForm();
    }

    /**
     * @param ProductOptionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(ProductOptionRequest $request, BaseHttpResponse $response)
    {
        $option = $this->globalOptionRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(GLOBAL_OPTION_MODULE_SCREEN_NAME, $request, $option));

        return $response
            ->setPreviousUrl(route('global-option.index'))
            ->setNextUrl(route('global-option.edit', $option->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $option = $this->globalOptionRepository->findOrFail($id, ['values']);

        event(new BeforeEditContentEvent($request, $option));

        page_title()->setTitle(trans('plugins/ecommerce::product-option.edit') . ' "' . $option->name . '"');

        return $formBuilder->create(GlobalOptionForm::class, ['model' => $option])->renderForm();
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $option = $this->globalOptionRepository->findOrFail($id);

            $this->globalOptionRepository->delete($option);

            event(new DeletedContentEvent(GLOBAL_OPTION_MODULE_SCREEN_NAME, $request, $option));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @param ProductOptionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, ProductOptionRequest $request, BaseHttpResponse $response)
    {
        $option = $this->globalOptionRepository->findOrFail($id);

        $this->globalOptionRepository->createOrUpdate($request->input(), ['id' => $id]);

        event(new UpdatedContentEvent(GLOBAL_OPTION_MODULE_SCREEN_NAME, $request, $option));

        return $response
            ->setPreviousUrl(route('global-option.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $option = $this->globalOptionRepository->findOrFail($id);
            $this->globalOptionRepository->delete($option);
            event(new DeletedContentEvent(GLOBAL_OPTION_MODULE_SCREEN_NAME, $request, $option));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function ajaxInfo($id)
    {
        return $this->globalOptionRepository->findOrFail($id, ['values']);
    }
}
