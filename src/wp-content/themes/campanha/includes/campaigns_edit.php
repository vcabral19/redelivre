<?php

$msgs = array();

if (!is_super_admin()) {
    print_msgs(array('error' => 'Você não tem permissão para editar campanhas.'));
    die;
}

if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
    try {
        $campaign = Campaign::getById($_REQUEST['id']);
    } catch (Exception $e) {
        echo $e->getMessage();
        die;
    }
}

if (!empty($_POST)) {
    $campaign->own_domain = filter_input(INPUT_POST, 'own_domain', FILTER_SANITIZE_URL);
    $campaign->candidate_number = filter_input(INPUT_POST, 'candidate_number', FILTER_SANITIZE_NUMBER_INT);
    $campaign->plan_id = filter_input(INPUT_POST, 'plan_id', FILTER_SANITIZE_NUMBER_INT);
    $campaign->state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_NUMBER_INT);
    $campaign->city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_NUMBER_INT);
    $campaign->observations = filter_input(INPUT_POST, 'observations', FILTER_SANITIZE_STRING);
    
    if ($campaign->validate()) {
        $campaign->update();
        
        $msgs = array('updated' => 'Campanha atualizada com sucesso.');
    } else {
        $msgs = $campaign->errorHandler->errors;
    }
}

?>

<div class="wrap">
    <h2 id="form_title">Editar campanha <?php echo $campaign->domain; ?></h2>
    
    <?php
    if (!empty($msgs)) {
        print_msgs($msgs);
    }
    ?>
    
    <form action="<?php echo admin_url(CAMPAIGN_EDIT_URL) . "&id={$campaign->id}"; ?>" method="post" enctype="multipart/form-data">
        <table class="form-table">
            <tbody>
                <tr class="form-field">
                    <th scope="row"><label for="domain">Sub-domínio</label></th>
                    <td>
                        <input type="text" value="<?php echo $campaign->domain; ?>" name="domain" style="display: block;" disabled="disabled">
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="own_domain">Domínio próprio (opcional)</label></th>
                    <td>
                        <input type="text" value="<?php if (isset($_POST['own_domain'])) { echo $_POST['own_domain']; } else if (isset($campaign->own_domain)) { echo $campaign->own_domain; } ?>" name="own_domain" style="display: block;">
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="candidate_number">Número do candidato</label></th>
                    <td>
                        <input type="text" value="<?php if (isset($_POST['candidate_number'])) { echo $_POST['candidate_number']; } else if (isset($campaign->candidate_number)) { echo $campaign->candidate_number; }?>" maxLength="5" name="candidate_number">
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="state">Localização</label></th>
                    <td>
                        <label for="state">Estado</label>
                        <select name="state" id="state">
                            <option value="">Selecione</option>
                            <?php
                            $campaignState = (isset($_POST['state'])) ? $_POST['state'] : $campaign->state;
                            
                            foreach (State::getAll() as $state):?>
                                <option value="<?php echo $state->id; ?>" <?php if ($campaignState == $state->id) echo ' selected="selected" '; ?>><?php echo $state->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        
                        <label for="city">Cidade</label>
                        <select name="city" id="city">
                            <?php
                            City::printCitiesSelectBox($campaignState, $campaign->city);
                            ?>
                        </select>
                    </td>
                </tr>
                <?php if (is_super_admin()) : ?>
                    <tr class="form-field">
                        <th scope="row"><label for="observations">Observações</label></th>
                        <td><input type="text" value="<?php if (isset($_POST['observations'])) { echo $_POST['observations']; } else if (isset($campaign->observations)) { echo $campaign->observations; }  ?>" name="observations"></td>
                    </tr>
                <?php endif; ?>
                <tr class="form-field">
                    <th scope="row"><label for="plan_id">Selecione um plano</label></th>
                    <td>
                        <?php
                        $campaignPlan = (isset($_POST['plan_id'])) ? $_POST['plan_id'] : $campaign->plan_id;
                        foreach (Plan::getAll() as $plan): ?>
                            <input type="radio" name="plan_id" class="radio" value="<?php echo $plan->id; ?>" <?php if ($campaignPlan == $plan->id) echo ' checked '; ?>> <?php echo $plan->name; ?>
                        <?php endforeach; ?>
                    </td>
                </tr>                
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" value="Atualizar" name="submit" class="button-primary">
        </p>
    </form>
</div>