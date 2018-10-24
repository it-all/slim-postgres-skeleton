<?php
declare(strict_types=1);

namespace Templates\Admin\Lists;

use Infrastructure\BaseMVC\Model\ListViewModels;
use Slim\Router;

class ObjectsListTemplate extends ListTemplate
{

    public function __construct(string $title, Router $router, int $columnCount, bool $deletesPermitted, array $displayItems, ?array $insertLinkInfo, string $sortColumn, bool $sortAscending, string $filterFormActionRoute, string $filterOpsList, string $filterValue, string $filterErrorMessage, string $filterFieldName, bool $isFiltered, string $resetFilterRoute, string $csrfNameKey, string $csrfName, string $csrfValueKey, string $csrfValue, bool $updatesPermitted, ?string $updateColumn, ?string $updateRoute, ?string $deleteRoute)
    {
        $headerFields = (count($displayItems) > 0) ? array_keys($displayItems[0]->getListViewFields()) : [];
        parent::__construct($title, $router, $columnCount, $deletesPermitted, $displayItems, $insertLinkInfo, $sortColumn, $sortAscending, $filterFormActionRoute, $filterOpsList, $filterValue, $filterErrorMessage, $filterFieldName, $isFiltered, $resetFilterRoute, $csrfNameKey, $csrfName, $csrfValueKey, $csrfValue, $updatesPermitted, $updateColumn, $updateRoute, $deleteRoute, $headerFields);
    }

    protected function getBodyRow(ListViewModels $model, int $rowNumber): string {

        $modelId = $model->getUniqueId();
        $showUpdateLink = $this->updatesPermitted && $model->isUpdatable();
        $showDeleteLink = $this->deletesPermitted && $model->isDeletable();
        
        return $this->getBodyRowCommon($rowNumber, $model->getListViewFields(), $showUpdateLink, $showDeleteLink, $modelId);
    }
}
