<?php 

/**
 *
 * @author Ivan Teleshun <teleshun.ivan@gmail.com>
 * @link http://molotoksoftware.com/
 * @copyright 2016 MolotokSoftware
 * @license GNU General Public License, version 3
 */

/**
 * 
 * This file is part of MolotokSoftware.
 *
 * MolotokSoftware is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * MolotokSoftware is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with MolotokSoftware.  If not, see <http://www.gnu.org/licenses/>.
 */



$prices = ProPrice::model()->findAll();

$userModel = Getter::userModel(); 


?>

<h3>ПРО-аккаунт</h3>

<?php if (Yii::app()->user->hasFlash('successful')): ?>
    <div class='alert alert-success'>
        <?=Yii::app()->user->getFlash('successful');?>
    </div>
<?php endif; ?>

<?php if ($userModel['pro'] == 1): ?>
    <?php
    $paidId = PaidServices::hasActivePro(Yii::app()->user->id);
    $paid = PaidServices::model()->findByPk($paidId);
    @$proAccount = Yii::app()->db->createCommand()
        ->select('*')
        ->from('service_pro_accounts')
        ->where(
            'id=:id',
            array(
                ':id' => $paid->services_id
            )
        )
        ->queryRow();
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">ПРО-аккаунт активен</div>
           <div class="panel-body">
               Дата истечения: <strong><?=$proAccount['completion_date'];?></strong>
        </div>
    </div>
<?php endif; ?>

<?php if (($textInfo = Setting::getByName('textInformationPro')) != ''): ?>
<div class="panel panel-default">
    <div class="panel-heading">Преимущества ПРО-аккаунта</div>
       <div class="panel-body">
           <?php echo $textInfo; ?>
    </div>
</div>
<?php endif;?>

<h4 class=",margint_top_30">Пакеты</h4>
<div class="row">
    <?php foreach ($prices as $price): ?>
        <div class="col-xs-4">
            <div class="panel panel-default">
                <div class="panel-heading"><?=$price->name;?></div>
                   <div class="panel-body">
                       Стоимость: <strong><?=floatval($price->price); ?> руб.</strong><br />
                       <a class="btn btn-success margint_top_30" href="<?= Yii::app()->createAbsoluteUrl('/buy/pro', array('id' => $price->id)); ?>">
                        <?php if ($userModel['pro'] != 1): ?>активировать<?php else: ?>продлить<?php endif; ?></a>
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>

    