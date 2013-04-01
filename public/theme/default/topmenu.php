<div class="menuLine">
		<div class="bc">
			<ul id="menuLi" class="menuLi">
				<li class="<?php echo ($this->controller == 'index')?'current':''?>"><a href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/index/dashboard"><span class="incons dashboard"></span><span class="hidemobile"><?php echo $this->translate('home')?></span><div class="clear"></div></a>
                    <ul>
                            <li>
                                <a title="" href="<?php echo $this->baseUrl ();?>/<?php echo $this->translate('lang')?>/index/administrator" class="">
                                <!-- <span class="icos-admin2"></span> -->Administrator</a>
                            </li>
                     </ul>
                </li>				
                <li class="<?php echo ($this->controller == 'organisme')?'current':''?>"><a href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/organisme/index"><span class="incons organisme"></span><span class="hidemobile"><?php echo $this->translate('organisme'); ?></span><div class="clear"></div></a>
                    <ul>
                            <li class="">
                                <a title="" href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/organisme/add" class="">
                                <!-- <span class="icos-admin2"></span> --><?php echo $this->translate('create_organisme'); ?></a>
                            </li>
                            <li>
                                <a title="" href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/organisme/index" class="this">
                                <!-- <span class="icos-admin2"></span> --><?php echo $this->translate('view_organisme'); ?></a>
                            </li>
                                                    	
                     </ul>
                </li>
                <li class="<?php echo ($this->controller == 'site')?'current':''?>"><a href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/site/index"><span class="incons site"></span><span class="hidemobile"><?php echo $this->translate('site'); ?></span><div class="clear"></div></a>
                     <ul>
					 <li class="">
                                <a title="" href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/site/add" class="">
                                <!-- <span class="icos-admin2"></span> --><?php echo $this->translate('create_site'); ?></a>
                            </li>
                           <li>
                                <a title="" href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/site/index" class="this">
                                <!-- <span class="icos-admin2"></span> --><?php echo $this->translate('view_site'); ?></a>
                            </li>
                                                    	
                     </ul> 
                </li>	
                <li class="<?php echo ($this->controller == 'equipement')?'current':''?>"><a href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/equipement/index"><span class="incons equipement"></span><span class="hidemobile"><?php echo $this->translate('equipement'); ?></span><div class="clear"></div></a></li>							
                <li class="<?php echo ($this->controller == 'maintenance')?'current':''?>"><a href="<?php echo $this->baseUrl();?>/<?php echo $this->translate('lang')?>/maintenance/index"><span class="incons equipement"></span><span class="hidemobile">Maintenance<?php //echo $this->translate('equipement'); ?></span><div class="clear"></div></a></li>

			</ul>
		</div>
	</div>