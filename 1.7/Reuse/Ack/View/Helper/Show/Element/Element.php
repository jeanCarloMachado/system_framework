<?php
	/**
	 * contém as declarações dos elementos html do ack
	 * @author jean
	 *
	 */
    class Reuse_ACK_View_Helper_Show_Element
    {
    	private static $action = null;
    	private static $permissionLevel = null;
    	
    	const DEFAULT_WIDTH_MODIFICATOR = 10;
    	
    	const LOWEST_EDITABLE_PERMISSION = 2;
    	
    	public static function setAction($name) 
    	{
    		self::$action = $name;
    	}
    	
    	public static function getAction()
    	{
    		if(!self::$action)
    			throw new Exception("action não setada");
    		
    		return self::$action;
    	}
    	
    	public static function setPermissionLevel($level)
    	{
    		self::$permissionLevel = $level;
    	}
    	 
    	public static function getPermissionLevel()
    	{
    		if(!self::$permissionLevel)
    			throw new Exception("nível de permissão não setado");
    		
    		return self::$permissionLevel;
    	}
    	
    	/***
    	 * mostra um imput na tela
    	 */
    	public static function input(System_Var $columObj,$title,$permissionLevel=null,$defVal="")
    	{
    		$title = ($title) ? $title : "Título não definido";
    		$permissionLevel = ($permissionLevel) ? $permissionLevel : self::getPermissionLevel();
    		?>
    			   	<fieldset>
                        <legend>
                                    <em><?= $title ?></em> <?= self::langOnTitleShower($columObj)?>
                                    
                       </legend>
                        
                        <input type="text" name="<?= $columObj->getColumnName() ?>" value="<?= (self::getAction() == "incluir" )? $defVal : $columObj->getVal() ?>" <? if ($permissionLevel < self::LOWEST_EDITABLE_PERMISSION) { ?>disabled="disabled"<? } ?> />
                    </fieldset>
    		<?php 
    	}
    	
    	/**
    	 * função que mostra a linguagem no título dos elementos
    	 * se existir idioma na coluna
    	 */
    	private static function langOnTitleShower(&$col)
    	{
    		if(System_Object_String::hasLangSuffix($col->getColName())) {
    	?>
    		<strong>[Português - <?= strtoupper(System_Language::getDefault()) ?>]</strong>
    	<?php 	
    		}
    	}
    	
		/**
		 * text area
		 * @param System_Var $columObj
		 * @param unknown $title
		 * @param string $permissionLevel
		 * @param string $defVal
		 */
    	public static function textArea(System_Var $columObj,$title,$permissionLevel=null,$defVal="")
    	{
    		$title = ($title) ? $title : "Título não definido";
    		$permissionLevel = ($permissionLevel) ? $permissionLevel : self::getPermissionLevel();
    		?>
    		    			<fieldset class=" textarea683x110">
    		                    	<div class="legend">
    		                                    <em><?= $title ?></em>
    		                                    <?= self::langOnTitleShower($columObj)?>
    		                       </div>
    		                       <? if ($permissionLevel!="2") { ?><div class="bloqueiaEditor"></div><? } ?>
    		                       <textarea  <?php if ($permissionLevel=="2") { ?>name="<?= $columObj->getColumnName() ?>"<? } else if($permissionLevel==1) { echo 'disabled="disabled"';} ?> rows="5" cols="50"><?= $columObj->getVal() ?></textarea>
    		                   </fieldset>
    		    		<?php 
    	}
    	/**
    	 * editor de texto
    	 * @param System_Var $columObj
    	 * @param unknown $title
    	 * @param string $permissionLevel
    	 * @param string $defVal
    	 */
    	public static function textEditor(System_Var $columObj,$title,$permissionLevel=null,$defVal="")
    	{
    		$title = ($title) ? $title : "Título não definido";
    		$permissionLevel = ($permissionLevel) ? $permissionLevel : self::getPermissionLevel();
    		?>
    			<fieldset class="editorTexto textarea683x110">
                    	<div class="legend">
                                    <em><?= $title ?></em>
                                     <?= self::langOnTitleShower($columObj)?>
                       </div>
                       <? if ($permissionLevel!="2") { ?><div class="bloqueiaEditor"></div><? } ?>
                       <textarea  <?php if ($permissionLevel=="2") { ?>name="<?= $columObj->getColumnName() ?>"<? } ?> rows="5" cols="50"><?= $columObj->getVal() ?></textarea>
                   </fieldset>
    		<?php 
    	}
    	/**
    	 * utilizado no visível e em status 
    	 * @param System_Var $columObj
    	 * @param unknown $title
    	 * @param string $permissionLevel
    	 */
    	public static function radioBoolean(System_Var $columObj,$title,$permissionLevel=null)
    	{
    		$title = ($title) ? $title : "Título não definido";
    		$permissionLevel = ($permissionLevel) ? $permissionLevel : self::getPermissionLevel();
    		
    			?>
    				   <fieldset class="radioGrup check<?= ucfirst($columObj->getColumnName()) ?>">
	    	               <legend><em><?= $title ?></em><button id="p_41" class="ajuda icone">(?)</button></legend>
	    	                <label><input type="radio" value="1" <? if ($permissionLevel=="2") { ?>name=<?= $columObj->getColumnName() ?><? } else { ?>disabled="disabled"<? } ?> <? if ($columObj->getBruteVal() || self::getAction() == "incluir") { ?> checked="checked"<? } ?>><span>Sim</span></label>
	    	                <label><input type="radio" value="0" <? if ($permissionLevel=="2") { ?>name=<?= $columObj->getColumnName() ?><? } else { ?>disabled="disabled"<? } ?> <? if (!$columObj->getBruteVal() && self::getAction() != "incluir") { ?> checked="checked"<? } ?>><span>Não</span></label>
    	               </fieldset><!-- END radioGrup -->
    			<?php 
    	}
    	
    	public static function default_list(Reuse_Ack_View_Show_Element_Container $container)
    	{
				
				
    			$rows = $container->getRows();
    			$defaultRow = reset($rows);
    			$urlPrefix = $container->getBaseUrl();
    			
    			$enableds = $container->getColsEnabled();
    			
    			
    		?>
    			
    			<div class="head">
					<button>
						<em><?= $container->getTitle() ?></em>
					</button>
					<p><?= $container->getText() ?></p>
				</div>
    				
    		
    			<div class="slide lista list_imagens">
    			
					<div class="header">
						<span class="borda"></span>
						<div>
							<!-- cabeçalho das colunas -->
							<?php foreach($defaultRow->getCols() as $col) { 
								if(empty($enableds) || in_array($col->getColName(), $enableds)) {
							?>
								<div class="id_image <?= strlen($col->getColName() ) * self::DEFAULT_WIDTH_MODIFICATOR ?>" style="width: 30px;">
									<button>
										<em><?= $col->getColNick() ?></em>
									</button>
								</div>
							<?php }
							} 
							?>
						</div>
						<span class="borda"></span>
					</div>
					
					<ol>
						<?php foreach($rows as $row ) { ?>
					
						<li id="<?= $row->getId()->getBruteVal() ?>" style="float: left; width: 100%;">
							<div>
								<!-- as colunas vão aqui -->
								<?php foreach($row->getCols() as $col) {

									if(empty($enableds) || in_array($col->getColName(), $enableds)) {
								?>
									<div class="id" style="float: left; width: <?= (strlen($col->getColName()) * self::DEFAULT_WIDTH_MODIFICATOR) ?>px;">
										<span class="checkLinha">
												<a href="<?= $container->getBaseUrl().$row->getId()->getBruteVal() ?>"><?= $col->getBruteVal() ?></a>
										</span>
									</div>
									
									
								<?php 
    									}
    								} 
    							?>
							</div>
						</li>
						<?php } ?>
					</ol>
					
					<button class="carregarMais" name="destaques" title="Carregar mais resultados">
						<span class="borda esq"></span>
						<em>Exibir mais resultados</em>
						<span class="borda dir"></span>
					</button>
				</div>
    		
    		<?php 
    	}
    	
		public static function language() 
		{
			$container = Reuse_Ack_Container::getInstance();
			$currRow = $container->getCurrentRow();
			//testa se a linha tem alguma coluna com outro idioma caso não,não mostra o menu
			$hasLang = false;
			foreach($currRow->getCols() as $col) {
					if(System_Object_String::hasLangSuffix($col->getColName())) {
						$hasLang = true;
						break;
					}
			}
			if($hasLang) {
			?>
			    	<fieldset class="menuIdiomas">
			    	<div class="legend"><em>Idioma</em><button class="ajuda icone" id="p_1">(?)</button></div>
			    	
			    	<div class="menuIdiomas-innner">
			    	<span><span></span><span></span></span>
			    	<div>
    	            			<?php foreach(Reuse_ACK_View_Helper_Retrieve_Languages::getAllObjects() as $lang) { ?>
    	                               <button name="<?= ($lang->getAbreviatura()->getVal()) ?>" title="<?= $lang->getNome()->getVal() ?> - [<?= strtoupper($lang->getAbreviatura()->getVal()) ?>]" class="<?= $currRow->hasLangContent($lang->getAbreviatura()->getVal()) ? "completo" : ""?> <?= ($lang->getAbreviatura()->getVal() == "pt") ? "	onView" : "" ?>"><em><?= $lang->getNome()->getVal() ?> - [<?= strtoupper($lang->getAbreviatura()->getVal()) ?>]</em></button>
    	                        <?php } ?>
    	                        </div>
    	                   <span><span></span><span></span></span>
    	                 </div>
    	           </fieldset><!-- END menuIdiomas -->
    	    <?php 
    	    
    	    }
    	 }
    	 
    	 /**
    	  * mostra um select com uma escolha (terminar a implmentação
    	  */
    	 public static function selectUnique()
    	 {

    	 	?>
    	 	<fieldset>
    	 	<legend>
    	 	<em>Status da imagem</em>
    	 	<button id="p_13" class="ajuda" title="O que é isso?">
    	 	<span>O que é isso?</span>
    	 	</button>
    	 	</legend>
    	 	<div class="select numeroLinhas">
    	 	<select name="<?= $row->getStatusId()->getColumnName() ?>">
    	 	<option value="0">Selecione</option>
    	 											
    	 											<?php foreach($imageStatus as $imageStatusRow) { ?>
    	 												<option <?= ($row->getStatusId()->getBruteVal() == $imageStatusRow->getId()->getBruteVal()) ? 'SELECTED="SELECTED"' : "" ?> value="<?= $imageStatusRow->getId()->getBruteVal() ?>"><?= $imageStatusRow->getName()->getVal() ?></option>
    	 											<?php } ?>
    	 											</select>
    	 										</div>
    	 								</fieldset>
    	 	<?php 
    	 	                            
    	 }
    	 
    	 
    }
?>

