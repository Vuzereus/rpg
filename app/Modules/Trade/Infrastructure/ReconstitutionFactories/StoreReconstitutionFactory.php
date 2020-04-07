<?php


namespace App\Modules\Trade\Infrastructure\ReconstitutionFactories;

use App\Item as ItemModel;
use App\Modules\Trade\Domain\Store;
use App\Modules\Trade\Domain\StoreId;
use App\Modules\Trade\Domain\StoreType;
use App\Store as StoreModel;
use App\Modules\Character\Domain\CharacterId;

class StoreReconstitutionFactory
{
    /**
     * @var StoreItemReconstitutionFactory
     */
    private $inventoryItemReconstitutionFactory;

    public function __construct(StoreItemReconstitutionFactory $inventoryItemReconstitutionFactory)
    {
        $this->inventoryItemReconstitutionFactory = $inventoryItemReconstitutionFactory;
    }

    public function reconstitute(StoreModel $storeModel): Store
    {
        $items = $storeModel->items->map(function (ItemModel $itemModel) {
            return $this->inventoryItemReconstitutionFactory->reconstitute($itemModel);
        });

        return new Store(
            StoreId::fromString($storeModel->getId()),
            CharacterId::fromString($storeModel->getCharacterId()),
            StoreType::ofType($storeModel->getType()),
            $items
        );
    }
}