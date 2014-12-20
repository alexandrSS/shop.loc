<?php

namespace console\modules\rbac\controllers;

use Yii;
use yii\console\Controller;
use console\modules\rbac\rules\GroupRule;

/**
 * RBAC console controller.
 */
class RbacController extends Controller
{
    /**
     * Initial RBAC action
     * @param integer $id Superadmin ID
     */
    public function actionInit($id = null)
    {
        $auth = Yii::$app->authManager;

        // Rules
        $groupRule = new GroupRule();

        $auth->add($groupRule);

        // Permissions
        // Основной контроллер backend приложенния
        $mainControllerBackend = $auth->createPermission('mainControllerBackend');
        $mainControllerBackend->description = 'Основной контроллер backend приложенния';
        $auth->add($mainControllerBackend);

        // Панель управления
        $bcDefaultController = $auth->createPermission('bcDefaultController');
        $bcDefaultController->description = 'Панель управления';
        $auth->add($bcDefaultController);

        // Пользователи
        $bcUserIndex = $auth->createPermission('bcUserIndex');
        $bcUserIndex->description = 'Список пользователей';
        $auth->add($bcUserIndex);

        $bcUserCreate = $auth->createPermission('bcUserCreate');
        $bcUserCreate->description = 'Создание пользователя';
        $auth->add($bcUserCreate);

        $bcUserUpdate = $auth->createPermission('bcUserUpdate');
        $bcUserUpdate->description = 'Обновление пользователя';
        $auth->add($bcUserUpdate);

        $bcUserDelete = $auth->createPermission('bcUserDelete');
        $bcUserDelete->description = 'Удаление пользователя';
        $auth->add($bcUserDelete);

        $bcUserBatchDelete = $auth->createPermission('bcUserBatchDelete');
        $bcUserBatchDelete->description = 'Удаление пользователей';
        $auth->add($bcUserBatchDelete);

        // Товар
        $bcArticleIndex = $auth->createPermission('bcArticleIndex');
        $bcArticleIndex->description = 'Список товара';
        $auth->add($bcArticleIndex);

        $bcArticleCreate = $auth->createPermission('bcArticleCreate');
        $bcArticleCreate->description = 'Создание товара';
        $auth->add($bcArticleCreate);

        $bcArticleUpdate = $auth->createPermission('bcArticleUpdate');
        $bcArticleUpdate->description = 'Обновление товара';
        $auth->add($bcArticleUpdate);

        $bcArticleDelete = $auth->createPermission('bcArticleDelete');
        $bcArticleDelete->description = 'Удаление товара';
        $auth->add($bcArticleDelete);

        //$bcArticleBatchDelete = $auth->createPermission('bcArticleBatchDelete');
        //$bcArticleBatchDelete->description = 'Удаление товаров';
        //$auth->add($bcArticleBatchDelete);

        // Категории товара
        $bcCategoryIndex = $auth->createPermission('bcCategoryIndex');
        $bcCategoryIndex->description = 'Список категорий товара';
        $auth->add($bcCategoryIndex);

        $bcCategoryCreate = $auth->createPermission('bcCategoryCreate');
        $bcCategoryCreate->description = 'Создание категорий товара';
        $auth->add($bcCategoryCreate);

        $bcCategoryUpdate = $auth->createPermission('bcCategoryUpdate');
        $bcCategoryUpdate->description = 'Обновление категорий товара';
        $auth->add($bcCategoryUpdate);

        $bcCategoryDelete = $auth->createPermission('bcCategoryDelete');
        $bcCategoryDelete->description = 'Удаление категорий товара';
        $auth->add($bcCategoryDelete);

        //$bcCategoryBatchDelete = $auth->createPermission('bcCategoryBatchDelete');
        //$bcCategoryBatchDelete->description = 'Удаление категорий товара';
        //$auth->add($bcCategoryBatchDelete);

        // Поставщики
        $bcProvidersIndex = $auth->createPermission('bcProvidersIndex');
        $bcProvidersIndex->description = 'Список поставщиков';
        $auth->add($bcProvidersIndex);

        $bcProvidersCreate = $auth->createPermission('bcProvidersCreate');
        $bcProvidersCreate->description = 'Создание поставщика';
        $auth->add($bcProvidersCreate);

        $bcProvidersUpdate = $auth->createPermission('bcProvidersUpdate');
        $bcProvidersUpdate->description = 'Обновление поставщика';
        $auth->add($bcProvidersUpdate);

        $bcProvidersDelete = $auth->createPermission('bcProvidersDelete');
        $bcProvidersDelete->description = 'Удаление поставщика';
        $auth->add($bcProvidersDelete);

        //$bcProvidersBatchDelete = $auth->createPermission('bcProvidersBatchDelete');
        //$bcProvidersBatchDelete->description = 'Удаление поставщиков';
        //$auth->add($bcProvidersBatchDelete);

        // Поставки
        $bcMakersIndex = $auth->createPermission('bcMakersIndex');
        $bcMakersIndex->description = 'Список поставок';
        $auth->add($bcMakersIndex);

        $bcMakersCreate = $auth->createPermission('bcMakersCreate');
        $bcMakersCreate->description = 'Создание поставки';
        $auth->add($bcMakersCreate);

        $bcMakersView = $auth->createPermission('bcMakersView');
        $bcMakersView->description = 'Создание поставки';
        $auth->add($bcMakersView);

        $bcMakersUpdate = $auth->createPermission('bcMakersUpdate');
        $bcMakersUpdate->description = 'Обновление поставки';
        $auth->add($bcMakersUpdate);

        $bcMakersDelete = $auth->createPermission('bcMakersDelete');
        $bcMakersDelete->description = 'Удаление поставки';
        $auth->add($bcMakersDelete);

        $bcMakersTest = $auth->createPermission('bcMakersTest');
        $bcMakersTest->description = 'Удаление поставки';
        $auth->add($bcMakersTest);

        //$bcMakersBatchDelete = $auth->createPermission('bcMakersBatchDelete');
        //$bcMakersBatchDelete->description = 'Удаление поставок';
        //$auth->add($bcMakersBatchDelete);

        // Поставки
        $bcDeliveryIndex = $auth->createPermission('bcDeliveryIndex');
        $bcDeliveryIndex->description = 'Список поставок';
        $auth->add($bcDeliveryIndex);

        $bcDeliveryCreate = $auth->createPermission('bcDeliveryCreate');
        $bcDeliveryCreate->description = 'Создание поставки';
        $auth->add($bcDeliveryCreate);

        $bcDeliveryUpdate = $auth->createPermission('bcDeliveryUpdate');
        $bcDeliveryUpdate->description = 'Обновление поставки';
        $auth->add($bcDeliveryUpdate);

        $bcDeliveryDelete = $auth->createPermission('bcDeliveryDelete');
        $bcDeliveryDelete->description = 'Удаление поставки';
        $auth->add($bcDeliveryDelete);

        $bcDeliveryDeleteMakers = $auth->createPermission('bcDeliveryDeleteMakers');
        $bcDeliveryDeleteMakers->description = 'Удаление поставки';
        $auth->add($bcDeliveryDeleteMakers);

        // Поставки
        $bcPostavkaIndex = $auth->createPermission('bcPostavkaIndex');
        $bcPostavkaIndex->description = 'Список поставок';
        $auth->add($bcPostavkaIndex);

        $bcPostavkaCreate = $auth->createPermission('bcPostavkaCreate');
        $bcPostavkaCreate->description = 'Создание поставки';
        $auth->add($bcPostavkaCreate);

        $bcPostavkaCreateTovar = $auth->createPermission('bcPostavkaCreateTovar');
        $bcPostavkaCreateTovar->description = 'Создание поставки';
        $auth->add($bcPostavkaCreateTovar);

        $bcPostavkaUpdate = $auth->createPermission('bcPostavkaUpdate');
        $bcPostavkaUpdate->description = 'Обновление поставки';
        $auth->add($bcPostavkaUpdate);

        $bcPostavkaDelete = $auth->createPermission('bcPostavkaDelete');
        $bcPostavkaDelete->description = 'Удаление поставки';
        $auth->add($bcPostavkaDelete);

        //$bcPostavkaBatchDelete = $auth->createPermission('bcPostavkaBatchDelete');
        //$bcPostavkaBatchDelete->description = 'Удаление поставок';
        //$auth->add($bcPostavkaBatchDelete);

        // События
        $bcLogIndex = $auth->createPermission('bcLogIndex');
        $bcLogIndex->description = 'Список событий';
        $auth->add($bcLogIndex);

        $bcLogView = $auth->createPermission('bcLogView');
        $bcLogView->description = 'Просмотр события';
        $auth->add($bcLogView);

        $bcLogDelete = $auth->createPermission('bcLogDelete');
        $bcLogDelete->description = 'Удаление события';
        $auth->add($bcLogDelete);

        $bcLogBatchDelete = $auth->createPermission('bcLogBatchDelete');
        $bcLogBatchDelete->description = 'Удаление событий';
        $auth->add($bcLogBatchDelete);

        // Продажа
        $bcProdajaIndex = $auth->createPermission('bcProdajaIndex');
        $bcProdajaIndex->description = 'Продажа';
        $auth->add($bcProdajaIndex);

        $bcProdajaArticle = $auth->createPermission('bcProdajaArticle');
        $bcProdajaArticle->description = 'Продажа';
        $auth->add($bcProdajaArticle);

        $bcProdajaSrokGodnosti = $auth->createPermission('bcProdajaSrokGodnosti');
        $bcProdajaSrokGodnosti->description = 'Продажа';
        $auth->add($bcProdajaSrokGodnosti);

        $bcProdajaKolichestvo = $auth->createPermission('bcProdajaKolichestvo');
        $bcProdajaKolichestvo->description = 'Продажа';
        $auth->add($bcProdajaKolichestvo);



        // Roles
        // Пользователь
        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $user->ruleName = $groupRule->name;
        $auth->add($user);

        // Админ
        $admin = $auth->createRole('admin');
        $admin->description = 'Админ';
        $admin->ruleName = $groupRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $mainControllerBackend);
        $auth->addChild($admin, $bcDefaultController);
        $auth->addChild($admin, $bcUserIndex);
        $auth->addChild($admin, $bcUserCreate);
        $auth->addChild($admin, $bcUserUpdate);
        $auth->addChild($admin, $bcArticleIndex);
        $auth->addChild($admin, $bcArticleCreate);
        $auth->addChild($admin, $bcArticleUpdate);
        $auth->addChild($admin, $bcCategoryIndex);
        $auth->addChild($admin, $bcCategoryCreate);
        $auth->addChild($admin, $bcCategoryUpdate);
        $auth->addChild($admin, $bcProvidersIndex);
        $auth->addChild($admin, $bcProvidersCreate);
        $auth->addChild($admin, $bcProvidersUpdate);
        $auth->addChild($admin, $bcMakersIndex);
        $auth->addChild($admin, $bcMakersView);
        $auth->addChild($admin, $bcMakersCreate);
        $auth->addChild($admin, $bcMakersUpdate);
        $auth->addChild($admin, $bcMakersTest);
        $auth->addChild($admin, $bcDeliveryIndex);
        $auth->addChild($admin, $bcDeliveryCreate);
        $auth->addChild($admin, $bcDeliveryUpdate);
        $auth->addChild($admin, $bcPostavkaIndex);
        $auth->addChild($admin, $bcPostavkaCreate);
        $auth->addChild($admin, $bcPostavkaCreateTovar);
        $auth->addChild($admin, $bcPostavkaUpdate);
        $auth->addChild($admin, $bcLogIndex);
        $auth->addChild($admin, $bcLogView);
        $auth->addChild($admin, $bcProdajaIndex);
        $auth->addChild($admin, $bcProdajaArticle);
        $auth->addChild($admin, $bcProdajaSrokGodnosti);
        $auth->addChild($admin, $bcProdajaKolichestvo);

        // Супер-Админ
        $superadmin = $auth->createRole('superadmin');
        $superadmin->description = 'Супер-Админ';
        $superadmin->ruleName = $groupRule->name;
        $auth->add($superadmin);
        $auth->addChild($superadmin, $admin);
        $auth->addChild($superadmin, $bcUserDelete);
        $auth->addChild($superadmin, $bcUserBatchDelete);
        $auth->addChild($superadmin, $bcArticleDelete);
        //$auth->addChild($superadmin, $bcArticleBatchDelete);
        $auth->addChild($superadmin, $bcCategoryDelete);
        //$auth->addChild($superadmin, $bcCategoryBatchDelete);
        $auth->addChild($superadmin, $bcProvidersDelete);
        //$auth->addChild($superadmin, $bcProvidersBatchDelete);
        $auth->addChild($superadmin, $bcMakersDelete);
        //$auth->addChild($superadmin, $bcMakersBatchDelete);
        $auth->addChild($superadmin, $bcDeliveryDelete);
        $auth->addChild($superadmin, $bcDeliveryDeleteMakers);
        //$auth->addChild($superadmin, $bcDeliveryBatchDelete);
        $auth->addChild($superadmin, $bcPostavkaDelete);
        //$auth->addChild($superadmin, $bcPostavkaBatchDelete);
        $auth->addChild($superadmin, $bcLogDelete);
        $auth->addChild($superadmin, $bcLogBatchDelete);

        // Superadmin assignments
        if ($id !== null) {
            $auth->assign($superadmin, $id);
        }
    }
}
