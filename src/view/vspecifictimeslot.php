<?php require_once PATH_VIEW . "header.php" ?>

<div style="margin: 2%;">
    <h3 class="" style="text-align: left; margin-top: 2%;">Total d'heures: <?= $utils->convertDecimalToHours($sumHours); ?> </h3>
    <hr>

    <h3 class="" style="text-align: left; margin-top: 2%;">Historique complet de <?= $speUser["name"] . " " . strtoupper($speUser["surname"]) ?></h3>
            <hr>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">id</th>
                    <th scope="col">Date</th>
                    <th scope="col">Durée</th>
                    <th scope="col">Description</th>
                    <th scope="col">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($timeslots as $timeslot){ 
        
                        ?>
                        <tr>
                            <th scope="row"><?php echo $timeslot->getId(); ?></th>
                            <td><?php echo $timeslot->getDate()->format("l j M"); ?></td>
                            <td><?php echo $utils->convertDecimalToHours($timeslot->getDuration()); ?></td>
                            <td><?php echo $timeslot->getDescription(); ?></td>
                            <td><?php 
                                $isValidated = $timeslot->getIsValidated();
                                if($isValidated == 2){?>
                                    <button type="button" class="btn btn-warning"><i class="fa-solid fa-clock"></i> En attente</button>
                                <?php }
                                elseif($isValidated == 0){ ?>
                                    <button type="button" class="btn btn-danger"><i class="fa-solid fa-xmark"></i> Refusé</button>
                                <?php }
                                else { ?>
                                    <button type="button" class="btn btn-success"><i class="fa-solid fa-check"></i> Validé</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                
                </tbody>
            </table>
</div>
