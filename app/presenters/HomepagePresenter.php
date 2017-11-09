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
      $this->template->id_screen = $this->getSession('def')->screenName = -1;
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




    $form->addHidden('id_screen')->setValue(isset($this->getSession('def')->screenName) ? $this->getSession('def')->screenName : -1);

    //defaults
    $form->setDefaults([
        'action' => 0,
        'id_screen' => -1
    ]);


    $form->addSubmit('process', 'Spustit')->setOption('id', 'process_id');
    $form->onSuccess[] = [$this, 'composerFormSucceeded'];

    return $form;
  }

  // called after form is successfully submitted
  public function composerFormSucceeded(UI\Form $form, $values)
  {
    $this->getSession()->setName('def');
    if ($this->isAjax()) {
      $id_screen = $this->getSession('def')->screenName;
      if ($id_screen && $id_screen !== -1) {
        $test = shell_exec(sprintf('cat /tmp/%s | grep "|||:::|||konec" | wc -l', $id_screen));
        if(trim($test)*1>0){
          $this->getSession('def')->screenName = -1;
        }
        $out = shell_exec(sprintf('tail -n300 /tmp/%s', $id_screen));
        $this->template->output = $this->bashColorToHtml(nl2br($out));
        $this->template->id_screen = $this->getSession('def')->screenName;
        $this->redrawControl('ajaxBash');
      } else {
        $screenName = md5(microtime(true));
        $command = sprintf('nohup sh /var/www/composer-handler/testloop.sh > /tmp/%s & ', $screenName);
        $this->getSession('def')->screenName = $screenName;
        $txt = shell_exec($command);
        $this->template->id_screen = $this->getSession('def')->screenName;
        $this->template->output = $this->bashColorToHtml(nl2br($txt));
        $this->redrawControl('ajaxBash');
      }
    }


//    $this->flashMessage('Odesláno v pohodě.');
//    $this->redirect('Homepage:');
  }

}
