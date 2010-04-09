<h3 class="sec_header"><?php echo date('l F j, Y', strtotime($context->release_date)); ?></h3>
                        
           
                       
                              
                            <?php
                            $columnIntro = '';
                            $column1 = '';
                            $column2 = '';
                            foreach ($context->getStories() as $key=>$story) {

                                $column = 'column1';

                                if ($story->sort_order % 2 == 0) {
                                    $column = 'column2';
                                }
                                if ($story->sort_order == 0) {
                                	$column = 'columnIntro';
                                }

                                $$column .= '
                                    <div class="story" id="story_'.$key.'">'
                                	. $savvy->render($story) .'
                                    </div>';
                            }
                            ?>
                       
                                <div id="newsColumnIntro" class="newsColumn">
                                <?php echo $columnIntro; ?>
                                </div>
                     
                         <div style="width:340px;padding:0 10px 0 0;float:left;">
                                <div id="newsColumn1" class="newsColumn">
                                <?php echo $column1; ?>
                                </div>
                         </div>
                         <div style="width:340px;padding:0 0 0 10px;float:right;">
                                <div id="newsColumn2" class="newsColumn">
                                <?php echo $column2; ?>
                                </div>
                         </div>
  