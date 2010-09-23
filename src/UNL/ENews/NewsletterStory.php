<?php
class UNL_ENews_NewsletterStory
{

    public $validStory;
    public $story;
    public $newsletter;
    public $newsroom;

    function __construct($options = array()){
        //Check to make sure the story is valid for the given newsroom.
        if(isset($options['newsID']) & isset($options['newsID']) & isset($options['newsID'])){
            if($this->newsletter = UNL_ENews_Newsletter::getById($options['newsID'])){
                if($this->story = UNL_ENews_Story::getById($options['id'])){
                    if($this->newsletter->hasStory($this->story)){
                        if($this->newsroom = UNL_ENews_Newsroom::getByID($this->newsletter->newsroom_id)){
                            if($this->newsroom->shortname == $options['shortname']){
                            }else{
                                throw new Exception('Not a valid news room name.');
                            }
                        }else{
                            throw new Exception('The newsroom does not exist!');
                        }
                    }else{
                        throw new Exception('The story does not belong to the given newsletter.');
                    }
                }else{
                    throw new Exception('This story does not exist.');
                }
            }else{
                throw new Exception('This newsletter does not exist');
            }
        }else{
            throw new Exception('The given input is not valid.');
        }
    }
}