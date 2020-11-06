<?php
	if (!isConnect('admin')) {
		throw new Exception('{{401 - Accès non autorisé}}');
	}
	$plugin = plugin::byId('AndroidTV');
	sendVarToJS('eqType', $plugin->getId());
	$eqLogics = eqLogic::byType($plugin->getId());
?>
<div class="row row-overflow">    
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br>
				<span>{{Configuration}}</span>
			</div>
		</div>
		<legend><i class="fas fa-table"></i> {{Mes équipements Android}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
		<div class="eqLogicThumbnailContainer">
			<?php
				foreach ($eqLogics as $eqLogic) {
					$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
					echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
					echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
					echo '<br>';
					echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
					echo '</div>';
				}
			?>
		</div>
	</div>
	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure">
					<i class="fa fa-cogs"></i>
					 {{Configuration avancée}}
				</a>
				<a class="btn btn-default btn-sm eqLogicAction" data-action="copy">
					<i class="fas fa-copy"></i>
					 {{Dupliquer}}
				</a>
				<a class="btn btn-sm btn-success eqLogicAction" data-action="save">
					<i class="fas fa-check-circle"></i>
					 {{Sauvegarder}}
				</a>
				<a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove">
					<i class="fas fa-minus-circle"></i>
					 {{Supprimer}}
				</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation">
				<a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay">
					<i class="fa fa-arrow-circle-left"></i>
				</a>
			</li>
			<li role="presentation" class="active">
				<a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="fas fa-tachometer"></i> 
					{{Equipement}}
				</a>
			</li>
			<li role="presentation">
				<a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="fas fa-list-alt"></i> 
					{{Commandes}}
				</a>
			</li>
			<li role="presentation">
				<a href="#apptab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="fas fa-list-alt"></i> 
					{{Liste des applications}}
			</a>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br />
				<div class="col-md-6">
					<div class="box">
						<div class="box-header backgroundColor">
							<h3 class="eqlogic-box-title">{{ Configuration générale }}</h3>
						</div>
						<div class="box-body">
							<form class="form-horizontal">
								<fieldset>
									<legend>{{Général}}</legend>
									<div class="form-group">
										<label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
										<div class="col-sm-3">
											<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
												<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label" >{{Objet parent}}</label>
										<div class="col-sm-3">
											<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
												<option value="">{{Aucun}}</option>
												<?php
													foreach (jeeObject::all() as $object) 
														echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">{{Catégorie}}</label>
										<div class="col-sm-9">
											<?php
												foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
													echo '<label class="checkbox-inline">';
													echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
													echo '</label>';
												}
											?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">{{Commentaire}}</label>
										<div class="col-sm-3">
											<textarea class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="commentaire" ></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"></label>
										<div class="col-md-8">
											<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
											<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="box">
						<div class="box-header backgroundColor">
							<h3 class="eqlogic-box-title">{{ Configuration équipement }}</h3>
						</div>
						<div class="box-body">
							<form class="form-horizontal">
								<fieldset>
									<legend>{{Paramètres}}</legend>
									<div class="form-group col-sm-12">
										<label class="col-sm-3 control-label">{{Assistant}}</label>
										<div class="col-sm-3">
											<a class="btn btn-infos" id="bt_configureAdb"><i class="fa fa-android"></i> {{Connecter un appareil Android}}</a>
										</div>
									</div>
									<div class="form-group col-sm-12">
										<label class="col-sm-3 control-label">{{Methode de connection}}</label>
										<div class="col-sm-6">
											<select class="eqLogicAttr form-control tooltips" data-l1key="configuration" data-l2key="type_connection" title="{{Veuillez préciser la methode de connection our votre appareil.}}">
												<option value="TCPIP">TCPIP</option>
												<option value="USB">USB</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">{{Adresse IP}}</label>
										<div class="col-sm-3">
											<input id="ip_address" data-inputmask="'alias': 'ip'" data-mask="" type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip_address"/>
										</div>
									</div>
								</fieldset>
								</br>
								<fieldset>
									<legend>{{Informations}}</legend>
									<div class="alert alert-info">
										{{Le choix de la connexion dépend principalement de votre appareil Android. Il y a des avantages et inconvénients pour chaque:<br>
										- USB: nécessite un cable et par conséquent que votre Android soit à proximité de votre Jeedom<br>
										- ADB: Ne nécessite aucune application tierce sur votre Android mais en fonction des équipements la connexion peut être capricieuse<br>
										- SSH: A venir (en cours d'étude de faisabilité)<br>}}
									</div>
									<div class="alert alert-danger">
										{{Si vous choisissez la connexion USB, seul 1 périphérique peut être contrôlé. Le plugin ne gère pas la connexion USB et TCPIP en même temps}}
									</div>
								</fieldset>
							</form>
						</div>
						<span id="serviceName" class="eqLogicAttr" data-l1key="configuration" data-l2key="serviceName" style="display:none;"></span>
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="commandtab">
			<table id="table_cmd" class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th>{{Nom}}</th><th>{{Type}}</th><th>{{Afficher}}</th><th>{{Action}}</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<div role="tabpanel" class="tab-pane" id="apptab">
			<table id="table_appli" class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th>{{Nom}}</th><th>{{Type}}</th><th>{{Afficher}}</th><th>{{Action}}</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>
<?php 
include_file('desktop', 'AndroidTV', 'js', 'AndroidTV');
include_file('core', 'plugin.template', 'js'); 
?>
