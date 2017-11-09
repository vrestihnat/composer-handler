<?php

namespace App\Presenters;

use Nette\Application\UI;
use Czubehead\BootstrapForms\BootstrapForm;
use Tracy\Debugger;

class HomepagePresenter extends BasePresenter
{

  public function renderDefault()
  {
    if (!$this->isAjax()) {
      $this->template->output = 'ready';
      $this->template->id_screen = $this->getSession('def')->screenName = -1;
    }
    $this->template->refresh = $this->getConfig()->get('terminalRefresh');
  }

  protected function createComponentComposerForm()
  {
    $form = new BootstrapForm;
//    $form = new UI\Form;
    $form->ajax = true;
    $form->addGroup('Nastavení');
    $form->addText('name', 'Název složky')->setRequired('Povinná položka ;)')->addRule(UI\Form::PATTERN, 'Špatný formát', '^(([a-z0-9_|-]+\.)*[a-z0-9_|-]+\.[a-z]+)|(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9-_]*[a-zA-Z0-9])$');
    $form->addSelect('exec', 'Interpret', array(
        'composer',
        'bash',
    ));
    $form->addRadioList('bash_action', 'Akce', array(
        'install', 'update', 'update <vendor/package>', //'custom (pro machry)'
    ))->setOption('id', 'action_list_id');
//            ->addCondition($form::EQUAL, 2)->toggle('package_name_id')->addCondition($form::EQUAL, 3)->toggle('expert_command_id');

    $form->addText('package_name', 'Název balíku')->setOption('id', 'package_name_id')->addRule(UI\Form::PATTERN, 'Špatný formát', '(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9])\/(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9])');

   // $form->addText('expert_command', 'Příkaz experta: composer ')->setOption('id', 'expert_command_id');

    //defaults
    $form->setDefaults([
        'action' => 0,
    ]);

    $form->addSubmit('process', 'Spustit')->setOption('id', 'process_id');
    $form->onSuccess[] = [$this, 'composerFormSucceeded'];
    $this->template->output = 'ready';
    return $form;
  }

  private function refreshTerminal($id_screen, $config)
  {
    $test = shell_exec(sprintf('cat /tmp/%s | grep "|||:::|||konec" | wc -l', $id_screen));
    if (trim($test) * 1 > 0) {
      $this->getSession('def')->screenName = -1;
    }
    $out = shell_exec(sprintf('tail -n300 /tmp/%s', $id_screen));
    $this->template->output = $this->bashColorToHtml(nl2br($out));
    $this->template->id_screen = $this->getSession('def')->screenName;
    $this->redrawControl('ajaxBash');
  }

  // called after form is successfully submitted
  public function composerFormSucceeded(UI\Form $form, $values)
  {
//    var_dump($values['name'],$values);exit;
    if ($this->isAjax()) {
      $config = $this->getConfig();
      $exec = $config->get('executor')[$values['exec']];
      $runCommand = $config->get('shellCommands')->runCommand;
      $action = $config->get('actions')->toArray()[$values['exec']][$values['bash_action']];
      $package = $values['bash_action'] == 2 ? trim($values['package_name']) : '';
      $expert = $values['bash_action'] == 3 ? trim($values['expert_command']) : '';
      $this->getSession()->setName('def');
      $applName = $values['name'];

      $id_screen = $this->getSession('def')->screenName;
      if ($id_screen && $id_screen !== -1) {
        $this->refreshTerminal($id_screen, $config);
      } else {
        $screenName = md5(microtime(true));
//        $command = sprintf('nohup sh /var/www/composer-handler/testloop.sh > /tmp/%s & ', $screenName);
//        $command = sprintf('nohup /bin/bash ' . APP_DIR . '/scripts/wrapper.sh "cd /var/www/%s && /usr/bin/composer install" /tmp/%s >> /tmp/%s & ', $applName ,$screenName, $screenName);
        $command = sprintf($runCommand, $applName, $exec, $action, $package . $expert, $screenName, $screenName);
        Debugger::log($command);
        $this->getSession('def')->screenName = $screenName;
        $txt = shell_exec($command);
        $this->template->id_screen = $this->getSession('def')->screenName;
        $this->template->output = $this->bashColorToHtml(nl2br($txt));
        $this->redrawControl('ajaxBash');
      }
    } else {
      $this->template->output = 'ready';
      $this->flashMessage('Odesláno v pohodě.');
      $this->redirect('Homepage:');
    }
  }

}
