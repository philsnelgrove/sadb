<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Application\Form\DimensionForm;

use Application\Entity\Dimension;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Doctrine\ORM;

class IndexController extends BaseController
{

    public function indexAction()
    {
         $form = new DimensionForm();
         $form->get('submit')->setValue('Add');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $dimension = new Dimension();
             $form->setInputFilter($dimension->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $dimension->exchangeArray($form->getData());
                 $this->getDimensionTable()->saveDimension($dimension);

                 // Redirect to list of dimensions
                 return $this->redirect()->toRoute('dimension');
             }
         }
         return array('form' => $form);
    }
    
    public function populate_dimensionAction()
    {
        $dimension_array = array(
            "post_story_adds_unique",
            "post_story_adds",
            "post_story_adds_by_action_type_unique",
            "post_story_adds_by_action_type",
            "post_impressions_unique",
            "post_impressions",
            "post_impressions_paid_unique",
            "post_impressions_paid",
            "post_impressions_organic_unique",
            "post_impressions_organic",
            "post_impressions_viral_unique",
            "post_impressions_viral",
            "post_impressions_by_story_type_unique",
            "post_impressions_by_story_type",
            "post_impressions_fan_unique",
            "post_impressions_fan",
            "post_impressions_fan_paid_unique",
            "post_impressions_fan_paid",
            "post_impressions_by_paid_non_paid_unique",
            "post_impressions_by_paid_non_paid",
            "post_video_length",
            "post_video_retention_graph",
            "post_video_retention_graph_autoplayed",
            "post_video_retention_graph_clicked_to_play",
            "post_video_avg_time_watched",
            "post_video_views",
            "post_video_views_unique",
            "post_video_views_autoplayed",
            "post_video_views_clicked_to_play",
            "post_video_views_organic",
            "post_video_views_organic_unique",
            "post_video_complete_views_organic",
            "post_video_complete_views_organic_unique",
            "post_video_views_paid",
            "post_video_views_paid_unique",
            "post_video_complete_views_paid",
            "post_video_complete_views_paid_unique",
            "post_video_complete_views_30s",
            "post_video_complete_views_30s_unique",
            "post_video_complete_views_30s_autoplayed",
            "post_video_complete_views_30s_clicked_to_play",
            "post_video_complete_views_30s_organic",
            "post_video_complete_views_30s_paid",
            "post_consumptions_unique",
            "post_consumptions",
            "post_consumptions_by_type_unique",
            "post_consumptions_by_type",
            "post_negative_feedback_unique",
            "post_negative_feedback",
            "post_negative_feedback_by_type_unique",
            "post_negative_feedback_by_type",
            "post_engaged_fan",
            "post_fan_reach",
            "post_storytellers",
            "post_storytellers_by_action_type",
            "post_engaged_users",
            "post_stories",
            "post_stories_by_action_type",
            "post_interests_impressions_unique",
            "post_interests_impressions",
            "post_interests_consumptions_unique",
            "post_interests_consumptions",
            "post_interests_consumptions_by_type_unique",
            "post_interests_consumptions_by_type",
            "post_interests_action_by_type_unique",
            "post_video_views_10s",
            "post_video_views_10s_unique",
            "post_video_views_10s_paid",
            "post_video_views_10s_organic",
            "post_video_views_10s_clicked_to_play",
            "post_video_views_10s_organic",
            "post_video_views_10s_autoplayed",
            "post_video_views_10s_sound_on",
            "post_video_views_sound_on"
        );
        
//         foreach ($dimension_array as $dimension)
//         {
//             $myDimension = new Dimension();
//             $myDimension->setName($dimension);
            
//             $this->getEntityManager()->persist($myDimension);
//         }
//         $this->getEntityManager()->flush();
        echo("dimension model populated");
        // return $dimension_array;
    }

    // action for both - edit and add
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $mode = "edit";

        $dimension = $this->getEntityManager()->find('Application\Entity\Dimension', $id);
        if(!$dimension instanceof Dimension) {
            $dimension = new Dimension();
            $mode = "add";
        }

        $form = new DimensionForm($this->getEntityManager());
        // assign hydrator
        $hydrator = new DoctrineHydrator($this->getEntityManager(), get_class($dimension));
        $form->setHydrator($hydrator);

        // bind object to form
        $form->bind($dimension);
        if($mode == "add") {
            $form->get('submit')->setValue('Add');    
        } else {
            $form->get('submit')->setAttribute('value', 'Save');   
        } 

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {

                if($mode == "add") {
                    $this->getEntityManager()->persist($dimension);
                    $msg = 'Add Dimension.';
                } else {
                    $msg = 'Dimension ___';
                }
                $this->getEntityManager()->flush();

                $this->FlashMessenger()->setNamespace(\Zend\Mvc\Controller\Plugin\FlashMessenger::NAMESPACE_INFO)
                        ->addMessage($msg);
                return $this->redirect()->toRoute('dimension');
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        // here be code for delete action
    }
}