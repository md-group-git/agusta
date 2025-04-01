<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Model;
use App\Entity\TechSpec;
use App\Enum\StockStatusEnum;

class CreateModelService
{
    public function createFromExisting(Model $existingModel): ?Model
    {
//        $created = new Model();
//
//        $created->setLineup($existingModel->getLineup());
//        $created->setName($existingModel->getName().' (Copy) ');
//        $created->setPrice($existingModel->getPrice());
//        $created->setSlogan($existingModel->getSlogan());
//        $created->setStockStatus(StockStatusEnum::OUT_OF_STOCK);
//        $created->setSpecial(true);
//        $created->setRide(false);
//
//        $created->setModelSpecs($existingModel->getModelSpecs());
//
//        foreach ($existingModel->getTechSpecs() as $existingTechSpec) {
//            $createdTechSpec = new TechSpec();
//            $createdTechSpec->setPosition($existingTechSpec->getPosition());
//            $createdTechSpec->setTechSection($existingTechSpec->getTechSection());
//            $createdTechSpec->setText($existingTechSpec->getText());
//            $created->addTechSpec($createdTechSpec);
//        }

        return null;
    }
}
