			<ul>
                <?php
                $user = UNL_ENews_Controller::getUser();
                if ($user) {
                    $newsroom = UNL_ENews_Newsroom::getByID($user->newsroom_id);
                }
                if (isset($context->options['newsroom'])) {
                    $newsroom = UNL_ENews_Newsroom::getByID($context->options['newsroom']);
                }
                if (isset($context->options['shortname'])) {
                    $newsroom = UNL_ENews_Newsroom::getByShortName($context->options['shortname']);
                }

                if (!$newsroom) {
                    // Default newsroom
                    $newsroom = UNL_ENews_Newsroom::getByID(1);
                }
                ?>
                <li><a href="<?php echo $newsroom->getURL();?>"><?php echo $newsroom->name;?></a>
                    <ul>
                        <li><a href="<?php echo UNL_ENews_Controller::getURL();?>?view=help">Help</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo $newsroom->getSubmitURL(); ?>">Submit A News Item</a>
                    <ul>
                        <li><a href="<?php echo UNL_ENews_Controller::getURL();?>?view=mynews">Your News Items</a></li>
                    </ul>
                </li>
                <?php
                if (false !== $user
                    && isset($user->newsroom_id)
                    && $user->hasNewsroomPermission($user->newsroom_id)) :
                    // This user is a newsroom admin.
                ?>
                <li><a href="<?php echo $newsroom->getURL(); ?>/manage">Manage News</a>
                    <?php
                    if ($user_newsrooms = $user->getNewsrooms()) {
                        if (count($user_newsrooms)) {
                            echo '<ul>';
                            foreach ($user_newsrooms as $newsroom) {
                                echo '<li><a href="'.$newsroom->getURL().'/manage">'.$newsroom->name.'</a></li>';
                            }
                            echo '</ul>';
                        }
                    }
                    ?>
                </li>
                <li><a href="<?php echo UNL_ENews_Controller::getURL(); ?>?view=preview">Build Newsletter</a>
                    <?php
                    if ($newsletters = $user->newsroom->getNewsletters(array('limit'=>3))) {
                        if (count($newsletters)) {
                            echo '<ul>';
                            // There is a user logged in
                            foreach($newsletters as $newsletter) {
                                if (isset($newsletter->release_date)) {
                                    echo '<li><a href="'.UNL_ENews_Controller::getURL().'?view=preview&amp;id='.$newsletter->id.'">'.str_replace(' 00:00:00', '', $newsletter->release_date).'</a></li>';
                                }
                            }
                            echo '<li><a href="'.UNL_ENews_Controller::getURL().'?view=newsletters&amp;limit=10">All newsletters</a></li>';
                            echo '</ul>';
                        }
                    }
                    ?>
                </li>
                <li><a href="<?php echo UNL_ENews_Controller::getURL(); ?>?view=newsroom">Edit Newsroom Details</a></li>
                <?php endif; ?>
            </ul>