<?php
# cancel recycling mission

# int id 			id de la mission
# int place 		id de la base orbitale

use Asylamba\Classes\Worker\CTR;
use Asylamba\Classes\Library\Http\Response;
use Asylamba\Classes\Exception\ErrorException;
use Asylamba\Classes\Exception\FormException;
use Asylamba\Modules\Athena\Model\RecyclingMission;

$session = $this->getContainer()->get('app.session');
$request = $this->getContainer()->get('app.request');
$response = $this->getContainer()->get('app.response');
$orbitalBaseManager = $this->getContainer()->get('athena.orbital_base_manager');
$recyclingMissionManager = $this->getContainer()->get('athena.recycling_mission_manager');

for ($i = 0; $i < $session->get('playerBase')->get('ob')->size(); $i++) { 
	$verif[] = $session->get('playerBase')->get('ob')->get($i)->get('id');
}

$missionId = $request->query->get('id');
$rPlace = $request->query->get('place');

if ($missionId !== FALSE AND $rPlace !== FALSE AND in_array($rPlace, $verif)) {
	
	$S_OBM1 = $orbitalBaseManager->getCurrentSession();
	$orbitalBaseManager->newSession(ASM_UMODE);
	$orbitalBaseManager->load(array('rPlace' => $rPlace));

	if ($orbitalBaseManager->size() == 1) {
		$base = $orbitalBaseManager->get();


		$S_REM1 = $recyclingMissionManager->getCurrentSession();
		$recyclingMissionManager->newSession(ASM_UMODE);
		$recyclingMissionManager->load(array('id' => $missionId, 'rBase' => $rPlace, 'statement' => RecyclingMission::ST_ACTIVE));

		if ($recyclingMissionManager->size() == 1) {
			$recyclingMissionManager->get()->statement = RecyclingMission::ST_BEING_DELETED;
			$response->flashbag->add('Ordre de mission annulé.', Response::FLASHBAG_SUCCESS);
		} else {
			throw new ErrorException('impossible de supprimer la mission.');
		}
		$recyclingMissionManager->changeSession($S_REM1);
	} else {
		throw new ErrorException('cette base orbitale ne vous appartient pas');
	}
	$orbitalBaseManager->changeSession($S_OBM1);
} else {
	throw new FormException('pas assez d\'informations pour supprimer une mission de recyclage');
}