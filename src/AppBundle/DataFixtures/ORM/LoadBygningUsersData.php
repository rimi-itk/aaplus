<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\DataFixtures\ORM;

use Ddeboer\DataImport\Writer\CallbackWriter;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Console\Output\ConsoleOutput;

use AppBundle\Entity\Bygning;
use AppBundle\Entity\User;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadBygningUsersData extends LoadData {
  protected $order = 3;
  protected $flush = true;

  protected function createWriter(ObjectManager $manager) {
    return new CallbackWriter(function ($item) use ($manager) {
      $bygning = $manager->getRepository('AppBundle:Bygning')->find($item['bygning_id']);
      if (!$bygning) {
        $this->writeError('No such Bygning: '.$item['bygning_id']);
      } else {
        $users = $manager->getRepository('AppBundle:User')->findBy(array('id' => explode(',', $item['user_ids'])));
        $bygning->setUsers(new ArrayCollection($users));
      }
    });
  }
}
