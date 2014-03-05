<?php
include_once ATHENA;
include_once ARES;

# affect a commander

# int id 	 		id du commandant

if (CTR::$get->exist('id')) {
	$commanderId = CTR::$get->get('id');
} elseif (CTR::$post->exist('id')) {
	$commanderId = CTR::$post->get('id');
} else {
	$commanderId = FALSE;
}


if ($commanderId !== FALSE) {
	$S_COM1 = ASM::$com->getCurrentSession();
	ASM::$com->newSession();
	ASM::$com->load(array('c.id' => $commanderId, 'c.rPlayer' => CTR::$data->get('playerId')));

	if (ASM::$com->size() == 1) {
		$commander = ASM::$com->get();
		
		// vider le commandant
		$commander->emptySquadrons();
		$commander->setStatement(4);

		CTR::$alert->add('Vous avez renvoyé votre commandant ' . $commander->getName() . '.', ALERT_STD_SUCCESS);

	} else {
		CTR::$alert->add('Ce commandant n\'existe pas ou ne vous appartient pas.', ALERT_STD_ERROR);
	}

	ASM::$com->changeSession($S_COM1);
} else {
	CTR::$alert->add('manque d\'information pour le traitement de la requête', ALERT_BUG_ERROR);
}

?>