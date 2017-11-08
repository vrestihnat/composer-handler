<?php

namespace App\Presenters;

use Nette\Application\UI;
use Czubehead\BootstrapForms\BootstrapForm;

class HomepagePresenter extends BasePresenter
{

  public function renderDefault()
  {
    if (!$this->isAjax()) {
      $this->template->output = 'ready';
    }
  }

  protected function createComponentComposerForm()
  {

    $form = new BootstrapForm;
//    $form = new UI\Form;
    $form->ajax = true;
    $form->addGroup('Nastavení');
    $form->addText('name', 'Název složky')->setRequired('Povinná položka ;)');
    $form->addSelect('exec', 'Interpret', array(
        'composer',
    ));
    $form->addRadioList('action', 'Akce', array(
        'install', 'update', 'update <vendor/package>', 'custom (pro machry)'
    ))->setOption('id', 'action_list_id');
//            ->addCondition($form::EQUAL, 2)->toggle('package_name_id')->addCondition($form::EQUAL, 3)->toggle('expert_command_id');

    $form->addText('package_name', 'Název balíku')->setOption('id', 'package_name_id');

    $form->addText('expert_command', 'Příkaz experta')->setOption('id', 'expert_command_id');


    //defaults
    $form->setDefaults([
        'action' => 0,
    ]);


    $form->addSubmit('process', 'Spustit');
    $form->onSuccess[] = [$this, 'composerFormSucceeded'];

    return $form;
  }

  // called after form is successfully submitted
  public function composerFormSucceeded(UI\Form $form, $values)
  {

    if ($this->isAjax()) {
//      $this->template->output = 'bleee';
//      $this->redrawControl('ajaxBash');
      if (($fp = popen("ls -ahl /", "r"))) {
        while (!feof($fp)) {
          $txt = fread($fp, 80);
          $this->template->output = $txt;
          $this->redrawControl('ajaxBash');
//          flush(); // you have to flush buffer
          break;
        }
        fclose($fp);
      }
      
    }


//    $this->flashMessage('Odesláno v pohodě.');
//    $this->redirect('Homepage:');
  }

}
