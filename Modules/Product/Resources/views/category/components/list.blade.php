<div class="row">
    <div class="col-lg-12">
        <table class="table" id="categoryDataTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('common.id') }}</th>
                    <th scope="col">{{ __('common.name') }}</th>
                    <th scope="col">{{ __('product.parent_category') }}</th>
                    @if(isModuleActive('MultiVendor'))
                    <th scope="col">{{ __('common.commission_rate') }}</th>
                    @endif
                    <th scope="col">{{ __('common.status') }}</th>
                    <th scope="col">{{ __('common.action') }}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
