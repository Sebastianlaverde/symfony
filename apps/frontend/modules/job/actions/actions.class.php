<?php

/**
 * job actions.
 *
 * @package    jobeet
 * @subpackage job
 * @author     Your name here
 * @version    SVN: $Id$
 */
class jobActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
{
  $this->categories = Doctrine_Core::getTable('JobeetCategory')->getWithJobs();
}

  public function executeFooBar(sfWebRequest $request)
{
  $this->foo = 'bar';
  $this->bar = array('bar', 'baz');
}


  public function executeShow(sfWebRequest $request)
  {
    $this->jobeet_job = $this->getRoute()->getObject();
  }

  public function executeNew(sfWebRequest $request)
{
  $jobeet_job = new JobeetJob();
  $jobeet_job->setType('full-time');
 
  $this->form = new JobeetJobForm($jobeet_job);
}
 
public function executeCreate(sfWebRequest $request)
{
  $this->form = new JobeetJobForm();
  $this->processForm($request, $this->form);
  $this->setTemplate('new');
}
 
public function executeEdit(sfWebRequest $request)
{
  $this->form = new JobeetJobForm($this->getRoute()->getObject());
}
 
public function executeUpdate(sfWebRequest $request)
{
  $this->form = new JobeetJobForm($this->getRoute()->getObject());
  $this->processForm($request, $this->form);
  $this->setTemplate('edit');
}
 
public function executeDelete(sfWebRequest $request)
{
  $request->checkCSRFProtection();
 
  $jobeet_job = $this->getRoute()->getObject();
  $jobeet_job->delete();
 
  $this->redirect('job/index');
}
 
protected function processForm(sfWebRequest $request, sfForm $form)
{
  $form->bind(
    $request->getParameter($form->getName()),
    $request->getFiles($form->getName())
  );
 
  if ($form->isValid())
  {
    $jobeet_job = $form->save();
 
    $this->redirect($this->generateUrl('job_show', $jobeet_job));
  }
}

  public function executePublish(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
  
    $jobeet_job = $this->getRoute()->getObject();
    $jobeet_job->publish();
  
    $this->getUser()->setFlash('notice', sprintf('Your job is now online for %s days.', sfConfig::get('app_active_days')));
  
    $this->redirect($this->generateUrl('job_show_user', $jobeet_job));
  }
}
