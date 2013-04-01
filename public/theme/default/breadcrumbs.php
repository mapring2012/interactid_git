	<div class="breadLine">
		<div class="bc">
			<ul id="breadcrumbs" class="breadcrumbs">
				<?php if($this->controller != 'index') {?>
				<li><a href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/index/dashboard"><?php echo $this->translate('home')?></a></li>
				<?php }?>				
				<li class="<?php echo ($this->action == 'index')?'current':''?>">
					<a href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/<?php
                    /*if factory controller*/
                    if($this->controller == 'factory')
                    {
                        echo 'index/administrator';
                    }
                    else if($this->controller == 'maintenanceadmin')
                    {
                       echo 'index/administrator'; 
                    }
                    else
                    {
                        echo $this->controller.'/index';  
                    }
                     ?>"><?php
                    if($this->controller == 'index')
                    {
                        $controller = $this->translate('home');
                    } else if($this->controller == 'organisme')
                    {
                        $controller = 'Organisme';
                    } else if($this->controller == 'site')
                    {
                        $controller = 'Site';
                    } else if($this->controller == 'typeequipement')
                    {
                        $controller = 'Type Equipement';
                    } else if($this->controller == 'equipement')
                    {
                        $controller = 'Equipement';
                    } else if($this->controller == 'factory')
                    {
                        $controller = 'Administror';
                    }
                     else if($this->controller == 'maintenanceadmin')
                    {
                        $controller = 'Administrator';
                    }
                    else
                    {
                        $controller = '';
                    }       
                    
                     echo $controller;?></a>
				</li>
				<?php if($this->action != 'index') {
				?>
				<li class="current">
					<a href="#"><?php echo $pageTitle;?></a>
				</li>
				<?php
				}?>				
			</ul>
		</div>
        
        <div class="breadLinks">
            <?php
            if(!empty($breadLinks))
            {
              echo $breadLinks;  
            }
             ?>
        </div>
	</div>