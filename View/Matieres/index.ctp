<?php $this->start('gestion'); ?>
	<div class="navbar"></div>
	<div class="row">
		<div class="col-sm-1"></div>
		<div class="col-sm-3">
			<div>
				<?= $this->Form->create("Matiere"); ?>
				<table class="table table-sm">
					<thead>
						<tr class="bg-primary">
							<td class="text-center text-light" colspan="2">Nouvelle matière</td>
						</tr>
						<?php if(isset($ajout_msg))
							echo "<tr><td colspan='2' class='text-center bg-light'>".$ajout_msg."</td></tr>";
						 ?>
					</thead>
					<tbody class="jumbotron">
						<tr>
							<td class="text-secondary">Code</td>
							<td>
								<?= $this->Form->input('code_mat',['type'=>'text','class'=>'form-control form-control-sm',
				      		 		'label'=> false, 'autocomplete' => 'off', 'placeholder'=>'exemple: MAT000']);?>	
							</td>
						</tr>
						<tr>
							<td class="text-secondary">Désignation:</td>
							<td>
								<?= $this->Form->input('libelle_mat', ['type'=>'text', 'class'=>'form-control form-control-sm',
				      		 		'label'=> false, 'autocomplete' => 'off', 'placeholder'=>'exemple: Anglais']);?>	
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<?= $this->Form->submit('Enregistrer',
								 ['class'=>'form-control form-control-sm']); ?>	
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" class="text-center bg-success text-light">
								Norme de code: MATXXX
							</td>
						</tr>
					</tfoot>
				</table>
				<?= $this->Form->end(); ?>		
			</div>
		</div>
		<div class="col-sm-1"></div>
		<div class="col">
			<input type="text" placeholder="Filtrer le tableau" 
				class="form-control form-control-sm ifilter">
			<div style="height: 60vh; overflow: auto;">	
				<table class="table-sm table small tfilter">
					<thead>
						<tr>
							<td colspan="3">
								<h6 class="text-center text-secondary">Liste des matières</h6>
							</td>
						</tr>
						<tr class="jumbotron">
							<td>Insertion</td>
							<td>Code</td>
							<td>Libellé
								<?php echo $this->Html->image('../img/edit-class-.ico',
									array('width'=> 20, 'height'=> 20)); ?>
							</td>
						</tr>
					</thead>
					<tbody>
						<?php $tab = array('MAT997'=>'', 'MAT998' =>'', 'MAT999'=>'') ;?>
						<?php foreach ($liste_matiere as $matiere): ;?>
							<tr>
								<td class="insert" style="cursor: pointer;">
									<?php echo $this->Html->image('../img/Add.ico',
									array('width'=> 15, 'height'=> 15)); ?>
								</td>
								<td><?= $matiere['Matiere']['code_mat'] ;?></td>
							<?php if(!array_key_exists($matiere['Matiere']['code_mat'], $tab)): ;?>
								<td contenteditable="true">
									<?= $matiere['Matiere']['libelle_mat'] ;?>
								</td>
								<?php else: ;?>
								<td class="text-danger">
									<?= $matiere['Matiere']['libelle_mat'] ;?>
								</td>
							<?php endif; ?>
							</tr>
						<?php endforeach ;?>
					</tbody>
				</table>
			</div>
			<div class="d-flex justify-content-around">
				<?= $this->element('paginateur'); ?>
				<h6 class="text-danger"><?=$nombre_matiere;?> matière(s) au total</h6>	
			</div>
		</div>

	</div>
	<!--insertion d'une matière -->
	<div id="insert_mat_dialogue" title="Nouvelle Matière">
		<h5>Insérer une matière suivant : <span id="libele_insert_conf"></span></h5>
		<div class="form-group">
		    <input type="text" class="form-control" id="libele_insert" placeholder="Libellé">
		</div>
		<button type="button" class="btn btn-primary" id="add_insert">Valider</button>
	</div>

	<script type="text/javascript" charset="utf8">
		$(document).ready(function(){
						//Insertion
			$('#insert_mat_dialogue').dialog({autoOpen: false});

			$('.tfilter tbody tr').on('click','.insert', function(){

				$('#libele_insert_conf').html('<b>' + $(this).closest('tr').find("td").eq(2).text() + '</b>');
				$('#insert_mat_dialogue').dialog('open');

				var code_mat = $(this).closest('tr').find("td").eq(1).text();

				$('#add_insert').click(function() {

					$.post('<?php echo $this->request->base; ?>/Matieres/inserer',
						{
							code_mat: code_mat,
							libelle_mat: $('#libele_insert').val()

						}, function(data){
							alert(data.replace('\\u00e8', 'è'));
							window.location.replace('<?php echo $this->request->base; ?>/Matieres/index');	
					});

				});
			});

			//Mis à jour
			$('.tfilter tbody td').on('keypress', function(event){
				if(event.which == 13){
					var libelle_mat = $(this).text().trim();

					$.post("<?php echo $this->request->base; ?>/Matieres/mettre_jour",
						{
							code_mat: $(this).closest('tr').find('td:eq(1)').text(),
							libelle_mat: libelle_mat
						}, 
						function(data){
							if(data != 1)alert(data);
							location.reload();
					});
					return false;
				}
			});
		});
	</script>
<?php $this->end(); ?>	