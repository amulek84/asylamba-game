<?php
/**
 * Place
 *
 * @author Jacky Casas
 * @copyright Expansion - le jeu
 *
 * @package Gaia
 * @update 21.04.13
*/

class Place { 
	# CONSTANTS
	const TYP_EMPTY = 0;
	const TYP_MS1 = 1;
	const TYP_MS2 = 2;
	const TYP_MS3 = 3;
	const TYP_ORBITALBASE = 4;
	const COEFFMAXRESOURCE = 600;

	# CONST PNJ COMMANDER
	const LEVELMAXVCOMMANDER = 15;
	const POPMAX 			 = 250;

	# CONST RESULT BATTLE
	const CHANGESUCCESS 						= 10;
	const CHANGEFAIL							= 11;
	const CHANGELOST							= 12;

	const LOOTEMPTYSSUCCESS 					= 20;
	const LOOTEMPTYFAIL							= 21;
	const LOOTPLAYERWHITBATTLESUCCESS			= 22;
	const LOOTPLAYERWHITBATTLEFAIL				= 23;
	const LOOTPLAYERWHITOUTBATTLESUCCESS		= 24;
	const LOOTLOST								= 27;

	const CONQUEREMPTYSSUCCESS 					= 30;
	const CONQUEREMPTYFAIL						= 31;
	const CONQUERPLAYERWHITBATTLESUCCESS		= 32;
	const CONQUERPLAYERWHITBATTLEFAIL			= 33;
	const CONQUERPLAYERWHITOUTBATTLESUCCESS		= 34;
	const CONQUERLOST							= 37;

	const COMEBACK 								= 40;

	// PLACE
	public $id = 0;
	public $rPlayer = 0;
	public $rSystem = 0;
	public $typeOfPlace = 0;
	public $position = 0;
	public $population = 0;
	public $coefResources = 0;
	public $coefHistory = 0;
	public $resources = 0; // de la place si $typeOfBase = 0, sinon de la base
	public $uPlace = '';

	// SYSTEM
	public $rSector = 0;
	public $xSystem = 0;
	public $ySystem = 0;
	public $typeOfSystem = 0;

	// SECTOR
	public $tax = 0;

	// PLAYER
	public $playerColor = 0;
	public $playerName = '';
	public $playerAvatar = '';
	public $playerStatus = 0;
	public $playerLevel = 0;

	// BASE
	public $typeOfBase = 0; // 0=empty, 1=ms1, 2=ms2, 3=ms3, 4=ob
	public $typeOfOrbitalBase;
	public $baseName = '';
	public $points = '';

	// OB
	public $levelCommercialPlateforme = 0;
	public $levelGravitationalModule = 0;
	public $antiSpyInvest = 0;

	// COMMANDER 
	public  $commanders = array();

	//uMode
	public $uMode = TRUE;

	public function getId() 							{ return $this->id; }
	public function getRPlayer() 						{ return $this->rPlayer; }
	public function getRSystem() 						{ return $this->rSystem; }
	public function getTypeOfPlace() 					{ return $this->typeOfPlace; }
	public function getPosition() 						{ return $this->position; }
	public function getPopulation() 					{ return $this->population; }
	public function getCoefResources() 					{ return $this->coefResources; }
	public function getCoefHistory() 					{ return $this->coefHistory; }
	public function getResources() 						{ return $this->resources; }
	public function getRSector() 						{ return $this->rSector; }
	public function getXSystem() 						{ return $this->xSystem; }
	public function getYSystem() 						{ return $this->ySystem; }
	public function getTypeOfSystem() 					{ return $this->typeOfSystem; }
	public function getTax() 							{ return $this->tax; }
	public function getPlayerColor() 					{ return $this->playerColor; }
	public function getPlayerName() 					{ return $this->playerName; }
	public function getPlayerAvatar() 					{ return $this->playerAvatar; }
	public function getPlayerStatus() 					{ return $this->playerStatus; }
	public function getTypeOfBase() 					{ return $this->typeOfBase; }
	public function getBaseName() 						{ return $this->baseName; }
	public function getPoints() 						{ return $this->points; }
	public function getLevelCommercialPlateforme() 		{ return $this->levelCommercialPlateforme; }
	public function getLevelGravitationalModule() 		{ return $this->levelGravitationalModule; }
	public function getAntiSpyInvest()					{ return $this->antiSpyInvest; }

	public function setId($v) 							{ $this->id = $v; }
	public function setRPlayer($v) 						{ $this->rPlayer = $v; }
	public function setRSystem($v) 						{ $this->rSystem = $v; }
	public function setTypeOfPlace($v) 					{ $this->typeOfPlace = $v; }
	public function setPosition($v) 					{ $this->position = $v; }
	public function setPopulation($v) 					{ $this->population = $v; }
	public function setCoefResources($v) 				{ $this->coefResources = $v; }
	public function setCoefHistory($v) 					{ $this->coefHistory = $v; }
	public function setResources($v) 					{ $this->resources = $v; }
	public function setRSector($v) 						{ $this->rSector = $v; }
	public function setXSystem($v) 						{ $this->xSystem = $v; }
	public function setYSystem($v) 						{ $this->ySystem = $v; }
	public function setTypeOfSystem($v) 				{ $this->typeOfSystem = $v; }
	public function setTax($v) 							{ $this->tax = $v; }
	public function setPlayerColor($v) 					{ $this->playerColor = $v; }
	public function setPlayerName($v) 					{ $this->playerName = $v; }
	public function setPlayerAvatar($v) 				{ $this->playerAvatar = $v; }
	public function setPlayerStatus($v) 				{ $this->playerStatus = $v; }
	public function setTypeOfBase($v) 					{ $this->typeOfBase = $v; }
	public function setBaseName($v) 					{ $this->baseName = $v; }
	public function setPoints($v) 						{ $this->points = $v; }
	public function setLevelCommercialPlateforme($v) 	{ $this->levelCommercialPlateforme = $v; }
	public function setLevelGravitationalModule($v) 	{ $this->levelGravitationalModule = $v; }
	public function setAntiSpyInvest($v)				{ $this->antiSpyInvest = $v; }

	public function uMethod() {
		$token = CTC::createContext('place');
		$now   = Utils::now();

		if (Utils::interval($this->uPlace, $now, 's') > 0) {
			# update time
			$days = Utils::intervalDates($now, $this->uPlace, 'd');
			$this->uPlace = $now;

			# RESOURCE
			if ($this->typeOfBase == self::TYP_EMPTY) {
				foreach ($days as $key => $day) {
					CTC::add($day, $this, 'uResources', array());
				}
			}

			$S_COM_PLACE1 = ASM::$com->getCurrentSession();
			ASM::$com->newSession();
			ASM::$com->load(
				array(
					'c.rDestinationPlace' => $this->id,
					'c.statement' => 2
				),
				array('c.dArrival', 'ASC')
			);

#-------------------------------------------------------------------------------
			include_once ARES;

			$places = array();
			$playerBonuses = array();
			for ($i = 0; $i < ASM::$com->size(); $i++) { 
				$c = ASM::$com->get($i);
				# fill the places
				$places[] = $c->getRBase();
				# fill&load the bonuses if needed
/* TODO */		if ($playerBonuses not contains key $c->rPlayer) {
					$bonus = new PlayerBonus($c->rPlayer);
					$bonus->load();
					$playerBonuses[$c->rPlayer] = $bonus;
				}
			}

			# load all the places at the same time
			$S_PLM1 = ASM::$plm->getCurrentSession();
			ASM::$plm->newSession();
			ASM::$plm->load(array('id' => $places));
			

			for ($i = 0; $i < ASM::$com->size(); $i++) { 
				$commander = ASM::$com->get($i);

				switch ($commander->travelType) {
					case Commander::MOVE: 
						//$this->tryToChangeBase($commander);
						if ($commander->dArrival <= $now AND $commander->rDestinationPlace != NULL) {					
							$place = ASM::$plm->getById($commander->rBase);
							$bonus = $playerBonuses[$commander->rPlayer];
							CTC::add($commander->dArrival, $this, 'uChangeBase', array($commander, $place, $bonus));
						}
						break;

					case Commander::LOOT: 
						//$this->tryToLoot($commander);
						if ($commander->dArrival <= $now AND $commander->rDestinationPlace != NULL) {					
							$place = ASM::$plm->getById($commander->rBase);
							$bonus = $playerBonuses[$commander->rPlayer];
							CTC::add($commander->dArrival, $this, 'uLoot', array($commander, $place, $bonus));
						}
						break;

					case Commander::COLO: 
						//$this->tryToConquer($commander);
						if ($commander->dArrival <= $now AND $commander->rDestinationPlace != NULL) {					
							$place = ASM::$plm->getById($commander->rBase);
							$bonus = $playerBonuses[$commander->rPlayer];
							CTC::add($commander->dArrival, $this, 'uConquer', array($commander, $place, $bonus));
						}
						break;

					case Commander::BACK: 
						//$this->comeBackToHome($commander);
						if ($commander->dArrival <= $now AND $commander->rDestinationPlace != NULL) {					
							include_once ATHENA;
							$S_OBM1 = ASM::$obm->getCurrentSession();

							ASM::$obm->newSession(FALSE);
							ASM::$obm->load(array('rPlace' => $commander->getRBase()));
							$base = ASM::$obm->get();
							ASM::$obm->changeSession($S_OBM1);

							CTC::add($commander->dArrival, $this, 'uComeBackHome', array($commander, $base));
						}
						break;
					default: 
						CTR::$alert->add('Cette action n\'existe pas.', ALT_BUG_INFO);
/* TODO */			#$commander->hasToU = TRUE;	#where to put it ?!
				}
				
			}

			ASM::$plm->changeSession($S_PLM1);
#-------------------------------------------------------------------------------

			/*for ($i = 0; $i < ASM::$com->size(); $i++) {
				$commander = ASM::$com->get($i);

				if ($commander->dArrival <= $now AND $commander->rDestinationPlace != NULL) {					
					CTC::add($commander->dArrival, $this, 'uTravel', array($commander));
				}
			}*/

			ASM::$com->changeSession($S_COM_PLACE1);
			
		}

		CTC::applyContext($token);
	}

	public function uResources() {
		$maxResources = $this->population * self::COEFFMAXRESOURCE;
		$this->resources += floor(PLM_RESSOURCECOEFF * $this->population * 24);

		if ($this->resources > $maxResources) {
			$this->resources = $maxResources;
		}
	}

	/*public function uTravel($commander) {
		include_once ARES;

		switch ($commander->travelType) {
			case Commander::MOVE: 
				$this->tryToChangeBase($commander);
				break;

			case Commander::LOOT: 
				LiveReport::$type = Commander::LOOT;
				LiveReport::$dFight = $commander->dArrival;
				$this->tryToLoot($commander);
				break;

			case Commander::COLO: 
				LiveReport::$type = Commander::COLO;
				LiveReport::$dFight = $commander->dArrival;
				$this->tryToConquer($commander);
				break;

			case Commander::BACK: 
				$this->comeBackToHome($commander);
				break;
			default: 
				CTR::$alert->add('Cette action n\'existe pas.', ALT_BUG_INFO);
			// FIX
			$commander->hasToU = TRUE;
			return $commander;
		}
	}*/

	# se poser
	private function uChangeBase($commander) {
		include_once ATHENA;
		# si la place et le commander ont le même joueur
		if ($this->rPlayer == $commander->getRPlayer() AND $this->typeOfBase == 4) {
			$maxCom = OrbitalBase::MAXCOMMANDERSTANDARD;
			if ($this->typeOfOrbitalBase == OrbitalBase::TYP_MILITARY || $this->typeOfOrbitalBase == OrbitalBase::TYP_CAPITAL) {
				$maxCom = OrbitalBase::MAXCOMMANDERMILITARY;
			}
			# si place a assez de case libre :
			if (count($this->commanders) < $maxCom) {
				$comLine1 = 0;
				$comLine2 = 0;

				foreach ($this->commanders as $com) {
					if ($com->line == 1) {
						$comLine1++;
					} else {
						$comLine2++;
					}
				}

				if ($comLine2 <= $comLine1) {
					$commander->line = 2;
				} else {
					$commander->line = 1;
				}

				# instance de la place d'envoie + suppr commandant de ses flottes
				# enlever à rBase le commandant
				$S_PLM10 = ASM::$plm->getCurrentSession();
				ASM::$plm->newSession();
				ASM::$plm->load(array('id' => $commander->getRBase()));
				for ($i = 0; $i < count(ASM::$plm->get()->commanders); $i++) {
					if (ASM::$plm->get()->commanders[$i]->id == $commander->id) {
						unset(ASM::$plm->get()->commanders[$i]);
						ASM::$plm->get()->commanders = array_merge(ASM::$plm->get()->commanders);
					}
				}
				ASM::$plm->changeSession($S_PLM10);
				# changer rBase commander
				$commander->rBase = $this->id;
				// $commander->rDestinationPlace = NULL;
				$commander->travelType = NULL;
				// $commander->rStartPlace = NULL;
				// $commander->dArrival = NULL;

				$commander->statement = Commander::AFFECTED;

				# ajouter à $this le commandant
				$this->commanders[] = $commander;

				# envoie de notif
				$this->sendNotif(self::CHANGESUCCESS, $commander);
			} else {
				# NON : comeBackToHome
				$S_PLM10 = ASM::$plm->getCurrentSession();
				ASM::$plm->newSession();
				ASM::$plm->load(array('id' => $commander->getRBase()));
				$home = ASM::$plm->get();
				$length = Game::getDistance($this->getXSystem(), $home->getXSystem(), $this->getYSystem(), $home->getYSystem());

				$playerBonus = new PlayerBonus($commander->rPlayer);
				$playerBonus->load();
				$duration = Game::getTimeToTravel($home, $this, $playerBonus->bonus);
				$commander->move($commander->rBase, $this->id, Commander::BACK, $length, $duration);
				ASM::$plm->changeSession($S_PLM10);

				$this->sendNotif(self::CHANGEFAIL, $commander);
			}
		} else {
			$S_PLM10 = ASM::$plm->getCurrentSession();
			ASM::$plm->newSession();
			ASM::$plm->load(array('id' => $commander->getRBase()));
			$home = ASM::$plm->get();
			$length = Game::getDistance($this->getXSystem(), $home->getXSystem(), $this->getYSystem(), $home->getYSystem());

			$playerBonus = new PlayerBonus($commander->rPlayer);
			$playerBonus->load();
			$duration = Game::getTimeToTravel($home, $this, $playerBonus->bonus);
			$commander->move($commander->rBase, $this->id, Commander::BACK, $length, $duration);
			ASM::$plm->changeSession($S_PLM10);

			$this->sendNotif(self::CHANGELOST, $commander);
		}
	}

	# piller
	private function uLoot($commander) {
		include_once ARES;
#-----------------------------------------------------------
		LiveReport::$type = Commander::LOOT;
		LiveReport::$dFight = $commander->dArrival;
#-----------------------------------------------------------

		if ($this->rPlayer == 0) {
			// $commander->rDestinationPlace = NULL;
			$commander->travelType = NULL;
			$commander->travelLength = NULL;
			// $commander->rStartPlace = NULL;
			// $commander->dArrival = NULL;


			# planète vide -> faire un combat
			$this->startFight($commander);

			# si gagné
			if ($commander->getStatement() != Commander::DEAD) {
				# piller la planète
				$this->lootAnEmptyPlace($commander);
				# comeBackToHome
				$S_PLM10 = ASM::$plm->getCurrentSession();
				ASM::$plm->newSession();
				ASM::$plm->load(array('id' => $commander->getRBase()));
				$home = ASM::$plm->get();
				$length = Game::getDistance($this->getXSystem(), $home->getXSystem(), $this->getYSystem(), $home->getYSystem());

				$playerBonus = new PlayerBonus($commander->rPlayer);
				$playerBonus->load();
				$duration = Game::getTimeToTravel($home, $this, $playerBonus->bonus);
				$commander->move($commander->rBase, $this->id, Commander::BACK, $length, $duration);
				ASM::$plm->changeSession($S_PLM10);

				#création du rapport
				$report = $this->createReport();

				$this->sendNotif(self::LOOTEMPTYSSUCCESS, $commander, $report);
			} else {

				# si il est mort
				# enlever le commandant de la session
				$S_PLM11= ASM::$plm->getCurrentSession();
				ASM::$plm->newSession();
				ASM::$plm->load(array('id' => $commander->getRBase()));
				for ($i = 0; $i < count(ASM::$plm->get()->commanders); $i++) {
					if (ASM::$plm->get()->commanders[$i]->getId() == $commander->getId()) {
						unset(ASM::$plm->get()->commanders[$i]);
						ASM::$plm->get()->commanders = array_merge(ASM::$plm->get()->commanders);
					}
				}
				ASM::$plm->changeSession($S_PLM11);
				
				#création du rapport
				$report = $this->createReport();

				$this->sendNotif(self::LOOTEMPTYFAIL, $commander, $report);
			}
		# si il y a une base
		} else {
			# planète à joueur: si $this->rColor != commandant->rColor
			if ($this->playerColor != $commander->getPlayerColor() && $this->playerLevel > 1) {
				// $commander->rDestinationPlace = NULL;
				$commander->travelType = NULL;
				$commander->travelLength = NULL;
				// $commander->rStartPlace = NULL;
				// $commander->dArrival = NULL;


				$dCommanders = array();
				foreach ($this->commanders AS $dCommander) {
					if ($dCommander->statement == Commander::AFFECTED && $dCommander->line == 1) {
						$dCommanders[] = $dCommander;
					}
				}

				if (count($dCommanders) != 0) {
				# il y a des commandants en défense : faire un combat avec un des commandants
					$aleaNbr = rand(0, count($dCommanders) - 1);
					$this->startFight($commander, $dCommanders[$aleaNbr], TRUE);

					# si il gagne
					if ($commander->getStatement() != COM_DEAD) {
						// piller la planète
						$this->lootAPlayerPlace($commander);
						// comeBackToHome
						$S_PLM10 = ASM::$plm->getCurrentSession();
						ASM::$plm->newSession();
						ASM::$plm->load(array('id' => $commander->getRBase()));
						$home = ASM::$plm->get();
						$length = Game::getDistance($this->getXSystem(), $home->getXSystem(), $this->getYSystem(), $home->getYSystem());

						$playerBonus = new PlayerBonus($commander->rPlayer);
						$playerBonus->load();
						$duration = Game::getTimeToTravel($home, $this, $playerBonus->bonus);
						$commander->move($commander->rBase, $this->id, Commander::BACK, $length, $duration);
						ASM::$plm->changeSession($S_PLM10);

						unset($this->commanders[$aleaNbr]);
						$this->commanders = array_merge($this->commanders);

						#création du rapport
						$report = $this->createReport();

						$this->sendNotif(self::LOOTPLAYERWHITBATTLESUCCESS, $commander, $report);


					} else {
					# s'il est mort
						#  enlever le commandant de la session
						$S_PLM10 = ASM::$plm->getCurrentSession();
						ASM::$plm->newSession();
						ASM::$plm->load(array('id' => $commander->getRBase()));
						for ($i = 0; $i < count(ASM::$plm->get()->commanders); $i++) {
							if (ASM::$plm->get()->commanders[$i]->getId() == $commander->getId()) {
								unset(ASM::$plm->get()->commanders[$i]);
								ASM::$plm->get()->commanders = array_merge(ASM::$plm->get()->commanders);
							}
						}

						#ajouter du prestige au défenseur synelectique
						if ($this->playerColor == 7) {
							$S_PAM = ASM::$pam->getCurrentSession();
							ASM::$pam->newSession();
							ASM::$pam->load(array('id' => $this->rPlayer));
							ASM::$pam->get()->factionPoint += Color::POINTDENFEND;
							ASM::$pam->changeSession($S_PAM);
						}

						ASM::$plm->changeSession($S_PLM10);

						#création du rapport
						$report = $this->createReport();

						$this->sendNotif(self::LOOTPLAYERWHITBATTLEFAIL, $commander, $report);
					}
				} else {
					$this->lootAPlayerPlace($commander);

					$S_PLM10 = ASM::$plm->getCurrentSession();
					ASM::$plm->newSession();
					ASM::$plm->load(array('id' => $commander->getRBase()));
					$home = ASM::$plm->get();
					$length = Game::getDistance($this->getXSystem(), $home->getXSystem(), $this->getYSystem(), $home->getYSystem());

					$playerBonus = new PlayerBonus($commander->rPlayer);
					$playerBonus->load();
					$duration = Game::getTimeToTravel($home, $this, $playerBonus->bonus);
					$commander->move($commander->rBase, $this->id, Commander::BACK, $length, $duration);
					ASM::$plm->changeSession($S_PLM10);

					$this->sendNotif(self::LOOTPLAYERWHITOUTBATTLESUCCESS, $commander);
				}
			# si c'est a même couleur
			} else {
				// $commander->rDestinationPlace = NULL;
				$commander->travelType = NULL;
				$commander->travelLength = NULL;
				// $commander->rStartPlace = NULL;

				$S_PLM10 = ASM::$plm->getCurrentSession();
				ASM::$plm->newSession();
				ASM::$plm->load(array('id' => $commander->getRBase()));
				$home = ASM::$plm->get();
				$length = Game::getDistance($this->getXSystem(), $home->getXSystem(), $this->getYSystem(), $home->getYSystem());

				$playerBonus = new PlayerBonus($commander->rPlayer);
				$playerBonus->load();
				$duration = Game::getTimeToTravel($home, $this, $playerBonus->bonus);
				$commander->move($commander->rBase, $this->id, Commander::BACK, $length, $duration);
				ASM::$plm->changeSession($S_PLM10);

				$this->sendNotif(self::LOOTLOST, $commander);
			}
		}
	}

	# conquest
	private function uConquer($commander) {
		include_once DEMETER;

#-------------------------------------------------------
		LiveReport::$type = Commander::COLO;
		LiveReport::$dFight = $commander->dArrival;
#-------------------------------------------------------

		if ($this->rPlayer != 0) {
			// $commander->rDestinationPlace = NULL;
			$commander->travelType = NULL;
			$commander->travelLength = NULL;
			// $commander->rStartPlace = NULL;
			// $commander->dArrival = NULL;


			if ($this->playerColor != $commander->getPlayerColor() && $this->playerLevel > 3) {
				for ($i = 0; $i < count($this->commanders) - 1; $i++) {
					if ($this->commanders[$i + 1]->line < $this->commanders[$i]->line) {
						$tempCom = $this->commanders[$i];
						$this->commanders[$i] = $this->commanders[$i + 1];
						$this->commanders[$i + 1] = $tempCom;
					}
				}

				$nbrBattle = 0;
				$reportIds = array();
				while ($nbrBattle < count($this->commanders)) {
					if ($this->commanders[$nbrBattle]->statement == Commander::AFFECTED) {

						$this->startFight($commander, $this->commanders[$nbrBattle], TRUE);

						# mort du commandant
						if ($commander->getStatement() == COM_DEAD) {
							$report = $this->createReport();
							$reportIds[] = $report;
							$nbrBattle++;
							break;
						}
					}
					#création du rapport
					$report = $this->createReport();
					$reportIds[] = $report;
					
					$nbrBattle++;
				}

				# victoire
				if ($commander->getStatement() != COM_DEAD) {
					include_once ATHENA;

					if ($nbrBattle == 0) {
						$this->sendNotif(self::CONQUERPLAYERWHITOUTBATTLESUCCESS, $commander);
					} else {
						$this->sendNotifForConquest(self::CONQUERPLAYERWHITBATTLESUCCESS, $commander, $reportIds);
					}

					# attribuer le prestige au joueur
					if ($commander->playerColor == 1 || $commander->playerColor == 4 || $commander->playerColor == 5) {
						$S_PAM = ASM::$pam->getCurrentSession();
						ASM::$pam->newSession();
						ASM::$pam->load(array('id' => $commander->rPlayer));
						$points = 0;
						switch ($commander->playerColor) {
							case 1:
								$points = Color::POINTCONQUER;
								break;
							case 4:
								$points = round($this->population);
								break;
							case 5:
								$points = ($this->coefResources - 45) * Color::COEFFPOINTCONQUER;
								break;
							default:
								$points = 0;
								break;
						}
						ASM::$pam->get()->factionPoint += $points;
						ASM::$pam->changeSession($S_PAM);
					}

					if ($this->playerColor == 1 || $this->playerColor == 4 || $this->playerColor == 5) {
						$S_PAM = ASM::$pam->getCurrentSession();
						ASM::$pam->newSession();
						ASM::$pam->load(array('id' => $this->rPlayer));
						$points = 0;
						switch ($commander->playerColor) {
							case 1:
								$points = Color::POINTCONQUER;
								break;
							case 4:
								$points = round($this->population);
								break;
							case 5:
								$points = ($this->coefResources - 44) * Color::COEFFPOINTCONQUER;
								break;
							default:
								$points = 0;
								break;
						}
						ASM::$pam->get()->factionPoint -= $points;
						ASM::$pam->changeSession($S_PAM);
					}

					#attribuer le joueur à la place
					$this->commanders = array();
					$this->rColor = $commander->playerColor;
					$this->rPlayer = $commander->rPlayer;
					# changer l'appartenance de la base (et de la place)
					ASM::$obm->changeOwnerById($this->id, $commander->getRPlayer());

					$this->commanders[] = $commander;

					$commander->rBase = $this->id;
					$commander->statement = Commander::AFFECTED;
					$commander->line = 1;

				# s'il est mort
				} else {
					for ($i = 0; $i < count($this->commanders); $i++) {
						if ($this->commanders[$i]->statement == COM_DEAD) {
							unset($this->commanders[$i]);
							$this->commanders = array_merge($this->commanders);
						}
					}
					
					#ajouter du prestige au défenseur synelectique
					if ($this->playerColor == 7) {
						$S_PAM = ASM::$pam->getCurrentSession();
						ASM::$pam->newSession();
						ASM::$pam->load(array('id' => $this->rPlayer));
						ASM::$pam->get()->factionPoint += Color::POINTDENFEND;
						ASM::$pam->changeSession($S_PAM);
					}

					$this->sendNotifForConquest(self::CONQUERPLAYERWHITBATTLEFAIL, $commander, $reportIds);
				}
			} else {
				$S_PLM10 = ASM::$plm->getCurrentSession();
				ASM::$plm->newSession();
				ASM::$plm->load(array('id' => $commander->getRBase()));
				$home = ASM::$plm->get();
				$length = Game::getDistance($this->getXSystem(), $home->getXSystem(), $this->getYSystem(), $home->getYSystem());
				
				$playerBonus = new PlayerBonus($commander->rPlayer);
				$playerBonus->load();
				$duration = Game::getTimeToTravel($home, $this, $playerBonus->bonus);
				$commander->move($commander->rBase, $this->id, Commander::BACK, $length, $duration);
				ASM::$plm->changeSession($S_PLM10);

				$this->sendNotif(self::CONQUERLOST, $commander);
			}
		# planète rebelle
		} else {

			// $commander->rDestinationPlace = NULL;
			$commander->travelType = NULL;
			$commander->travelLength = NULL;
			// $commander->rStartPlace = NULL;
			// $commander->dArrival = NULL;


			# faire un combat
			$this->startFight($commander);

			if ($commander->getStatement() !== COM_DEAD) {
				
				# attribuer le rPlayer à la Place !
				$this->rPlayer = $commander->rPlayer;
				$this->commanders[] = $commander;

				#attibuer le commander à la place
				$commander->rBase = $this->id;
				$commander->statement = COM_AFFECTED;
				$commander->line = 1;

				# créer une Base
				include_once ATHENA;
				$ob = new OrbitalBase();
				$ob->rPlace = $this->id;
				$ob->setRPlayer($commander->getRPlayer());
				$ob->setName('Base de ' . $commander->getPlayerName());
				$ob->iSchool = 500;
				$ob->iAntiSpy = 500;
				$ob->resourcesStorage = 2000;
				$ob->uOrbitalBase = Utils::now();
				$ob->dCreation = Utils::now();
				$ob->updatePoints();

				$_OBM = ASM::$obm->getCurrentSession();
				ASM::$obm->newSession();
				ASM::$obm->add($ob);
				ASM::$obm->changeSession($_OBM);

				if ($commander->playerColor == 4 || $commander->playerColor == 5) {
					$S_PAM = ASM::$pam->getCurrentSession();
					ASM::$pam->newSession();
					ASM::$pam->load(array('id' => $commander->rPlayer));
					$points = 0;
					switch ($commander->playerColor) {
						case 4:
							$points = round($this->population);
							break;
						case 5:
							$points = ($this->coefResources - 44) * Color::COEFFPOINTCONQUER;
							break;
						default:
							$points = 0;
							break;
					}
					ASM::$pam->get()->factionPoint += $points;
					ASM::$pam->changeSession($S_PAM);
				}


				if (CTR::$data->get('playerId') == $commander->getRPlayer()) { 
					CTRHelper::addBase('ob', 
						$ob->getId(), 
						$ob->getName(), 
						$this->rSector, 
						$this->rSystem,
						'1-' . Game::getSizeOfPlanet($this->population),
						OrbitalBase::TYP_NEUTRAL);
				}
				
				#création du rapport
				$report = $this->createReport();

				$this->sendNotif(self::CONQUEREMPTYSSUCCESS, $commander, $report);
			# s'il est mort
			} else {
				
				#création du rapport
				$report = $this->createReport();

				$this->sendNotif(self::CONQUEREMPTYFAIL, $commander);
				# enlever le commandant de la session
				$S_PLM10 = ASM::$plm->getCurrentSession();
				ASM::$plm->newSession();
				ASM::$plm->load(array('id' => $commander->getRBase()));
				for ($i = 0; $i < count(ASM::$plm->get()->commanders); $i++) {
					if (ASM::$plm->get()->commanders[$i]->getId() == $commander->getId()) {
						unset(ASM::$plm->get()->commanders[$i]);
						ASM::$plm->get()->commanders = array_merge(ASM::$plm->get()->commanders);
					}
				}
				ASM::$plm->changeSession($S_PLM10);
			}
		}
	}


	# retour à la maison
	private function uComeBackHome($commander) {
		include_once ATHENA;
		// $commander->rDestinationPlace = NULL;
		$commander->travelType = NULL;
		$commander->travelLength = NULL;
		// $commander->rStartPlace = NULL;
		$commander->dArrival = NULL;


		$commander->statement = Commander::AFFECTED;

		$this->sendNotif(self::COMEBACK, $commander);

		if ($commander->getResourcesTransported() > 0) {
			$S_OBM10 = ASM::$obm->getCurrentSession();

			ASM::$obm->newSession(FALSE);
			ASM::$obm->load(array('rPlace' => $commander->getRBase()));
			ASM::$obm->get()->increaseResources($commander->resources);
			$commander->resources = 0;

			ASM::$obm->changeSession($S_OBM10);
		}
	}

	private function lootAnEmptyPlace($commander) {
		include_once ATHENA;
		include_once ZEUS;

		$bonus = 0;
		if ($commander->rPlayer != CTR::$data->get('playerId')) {
			$playerBonus = new PlayerBonus($commander->rPlayer);
			$playerBonus->load();
			$bonus = $playerBonus->bonus->get(PlayerBonus::SHIP_CONTAINER);
		} else {
			$bonus = CTR::$data->get('playerBonus')->get(PlayerBonus::SHIP_CONTAINER);
		}

		$storage = $commander->getPev() * Commander::COEFFLOOT;
		$storage += round($storage * ((2 * $bonus) / 100));
		$resourcesLooted = 0;

		if ($storage > $this->resources) {
			$ressouresLooted = $this->resources;
		} else {
			$ressouresLooted = $storage;
		}

		$this->resources -= $ressouresLooted;
		$commander->resources = $ressouresLooted;
		LiveReport::$resources = $ressouresLooted;
	}

	private function lootAPlayerPlace($commander) {
		include_once ATHENA;
		include_once ZEUS;

		$bonus = 0;
		if ($commander->rPlayer != CTR::$data->get('playerId')) {
			$playerBonus = new PlayerBonus($commander->rPlayer);
			$playerBonus->load();
			$bonus = $playerBonus->bonus->get(PlayerBonus::SHIP_CONTAINER);
		} else {
			$bonus = CTR::$data->get('playerBonus')->get(PlayerBonus::SHIP_CONTAINER);
		}

		$S_OBM1 = ASM::$obm->getCurrentSession();
		ASM::$obm->newSession();
		ASM::$obm->load(array('rPlace' => $this->id));
		$base = ASM::$obm->get();

		$resourcesToLoot = $base->getResourcesStorage() - Commander::LIMITTOLOOT;

		$storage = $commander->getPev() * Commander::COEFFLOOT;
		$storage += round($storage * ((2 * $bonus) / 100));
		$resourcesLooted = 0;

		$resourcesLooted = ($storage > $resourcesToLoot) ? $resourcesToLoot : $storage;

		if ($resourcesLooted > 0) {
			$base->decreaseResources($resourcesLooted);
			$commander->resources = $resourcesLooted;
			LiveReport::$resources = $resourcesLooted;
		}
		ASM::$obm->changeSession($S_OBM1);
	}

	private function startFight($commander, $enemyCommander = NULL, $pvp = FALSE) {
		if ($pvp == TRUE) {
			$commander->setArmy();
			$enemyCommander->setArmy();
			$fc = new FightController();
			$fc->startFight($commander, $enemyCommander);
		} else {
			$commander->setArmy();
			$computerCommander = $this->createVirtualCommander();
			$fc = new FightController();
			$fc->startFight($commander, $computerCommander);
		}
	}

	private function createReport() {
		include_once ARES;
		$report = new Report();

		$report->rPlayerAttacker = LiveReport::$rPlayerAttacker;
		$report->rPlayerDefender =  LiveReport::$rPlayerDefender;
		$report->rPlayerWinner = LiveReport::$rPlayerWinner;
		$report->avatarA = LiveReport::$avatarA;
		$report->avatarD = LiveReport::$avatarD;
		$report->nameA = LiveReport::$nameA;
		$report->nameD = LiveReport::$nameD;
		$report->levelA = LiveReport::$levelA;
		$report->levelD = LiveReport::$levelD;
		$report->experienceA = LiveReport::$experienceA;
		$report->experienceD = LiveReport::$experienceD;
		$report->palmaresA = LiveReport::$palmaresA;
		$report->palmaresD = LiveReport::$palmaresD;
		$report->resources = LiveReport::$resources;
		$report->expCom = LiveReport::$expCom;
		$report->expPlayerA = LiveReport::$expPlayerA;
		$report->expPlayerD = LiveReport::$expPlayerD;
		$report->rPlace = $this->id;
		$report->type = LiveReport::$type;
		$report->round = LiveReport::$round;
		$report->importance = LiveReport::$importance;
		$report->squadrons = LiveReport::$squadrons;
		$report->dFight = LiveReport::$dFight;
		$report->placeName = ($this->baseName == '') ? 'planète rebelle' : $this->baseName;
		$id = ASM::$rpm->add($report);
		LiveReport::clear();

		return $id;
	}

	private function sendNotif($case, $commander, $report = NULL) {
		include_once HERMES;

		switch ($case) {
			case self::CHANGESUCCESS:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Déplacement réussi');
				$notif->addBeg()
					->addTxt('Votre offier ')
					->addLnk('fleet/commander-' . $commander->getId(), $commander->getName())
					->addTxt(' est arrivé sur ')
					->addLnk('map/base-' . $this->id, $this->baseName)
					->addTxt('.')
					->addEnd();
				ASM::$ntm->add($notif);
				break;

			case self::CHANGEFAIL:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Déplacement raté');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId(), $commander->getName())
					->addTxt(' n\'a pas pu se poser sur ')
					->addLnk('map/base-' . $this->id, $this->baseName)
					->addTxt(' car il y a déjà trop d\'officiers autour de la planète.')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::CHANGELOST:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Déplacement raté');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId(), $commander->getName())
					->addTxt(' n\'est pas arrivé sur ')
					->addLnk('map/base-' . $this->id, $this->baseName)
					->addTxt('. Cette base ne vous appartient pas. Elle a pu être conquise entre temps.')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::LOOTEMPTYSSUCCESS:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Pillage réussi');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId() . '/sftr-3', $commander->getName())
					->addTxt(' a pillé la planète rebelle située aux coordonnées ')
					->addLnk('map/place-' . $this->id, Game::formatCoord($this->xSystem, $this->ySystem, $this->position, $this->rSector))
					->addTxt('.')
					->addSep()
					->addBoxResource('resource', Format::number($commander->getResourcesTransported()), 'ressources pillées')
					->addBoxResource('xp', '+ ' . Format::number($commander->earnedExperience), 'expérience de l\'officier')
					->addSep()
					->addLnk('fleet/view-archive/report-' . $report, 'voir le rapport')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::LOOTEMPTYFAIL:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Pillage raté');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/view-memorial', $commander->getName())
					->addTxt(' est tombé lors de l\'attaque de la planète rebelle située aux coordonnées ')
					->addLnk('map/place-' . $this->id, Game::formatCoord($this->xSystem, $this->ySystem, $this->position, $this->rSector))
					->addTxt('.')
					->addSep()
					->addTxt('Il a désormais rejoint le Mémorial. Que son âme traverse l\'Univers dans la paix.')
					->addSep()
					->addLnk('fleet/view-archive/report-' . $report, 'voir le rapport')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::LOOTPLAYERWHITBATTLESUCCESS:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Pillage réussi');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId() . '/sftr-3', $commander->getName())
					->addTxt(' a pillé la planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $this->rPlayer, $this->playerName)
					->addTxt('.')
					->addSep()
					->addBoxResource('resource', Format::number($commander->getResourcesTransported()), 'ressources pillées')
					->addBoxResource('xp', '+ ' . Format::number($commander->earnedExperience), 'expérience de l\'officier')
					->addSep()
					->addLnk('fleet/view-archive/report-' . $report, 'voir le rapport')
					->addEnd();
				ASM::$ntm->add($notif);

				$notif = new Notification();
				$notif->setRPlayer($this->rPlayer);
				$notif->setTitle('Rapport de pillage');
				$notif->addBeg()
					->addTxt('L\'officier ')
					->addStg($commander->getName())
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $commander->getRPlayer(), $commander->getPlayerName())
					->addTxt(' a pillé votre planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt('.')
					->addSep()
					->addBoxResource('resource', Format::number($commander->getResourcesTransported()), 'ressources pillées')
					->addSep()
					->addLnk('fleet/view-archive/report-' . $report, 'voir le rapport')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::LOOTPLAYERWHITBATTLEFAIL:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Pillage raté');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/view-memorial', $commander->getName())
					->addTxt(' est tombé lors du pillage de la planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $this->rPlayer, $this->playerName)
					->addTxt('.')
					->addSep()
					->addTxt('Il a désormais rejoint le Mémorial. Que son âme traverse l\'Univers dans la paix.')
					->addSep()
					->addLnk('fleet/view-archive/report-' . $report, 'voir le rapport')
					->addEnd();
				ASM::$ntm->add($notif);

				$notif = new Notification();
				$notif->setRPlayer($this->rPlayer);
				$notif->setTitle('Rapport de combat');
				$notif->addBeg()
					->addTxt('L\'officier ')
					->addStg($commander->getName())
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $commander->getRPlayer(), $commander->getPlayerName())
					->addTxt(' a attaqué votre planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt('.')
					->addSep()
					->addTxt('Vous avez repoussé l\'ennemi avec succès.')
					->addSep()
					->addLnk('fleet/view-archive/report-' . $report, 'voir le rapport')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::LOOTPLAYERWHITOUTBATTLESUCCESS:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Pillage réussi');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId() . '/sftr-3', $commander->getName())
					->addTxt(' a pillé la planète non défendue ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $this->rPlayer, $this->playerName)
					->addTxt('.')
					->addSep()
					->addBoxResource('resource', Format::number($commander->getResourcesTransported()), 'ressources pillées')
					->addBoxResource('xp', '+ ' . Format::number($commander->earnedExperience), 'expérience de l\'officier')
					->addEnd();
				ASM::$ntm->add($notif);

				$notif = new Notification();
				$notif->setRPlayer($this->rPlayer);
				$notif->setTitle('Rapport de pillage');
				$notif->addBeg()
					->addTxt('L\'officier ')
					->addStg($commander->getName())
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $commander->getRPlayer(), $commander->getPlayerName())
					->addTxt(' a pillé votre planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt('. Aucune flotte n\'était en position pour la défendre. ')
					->addSep()
					->addBoxResource('resource', Format::number($commander->getResourcesTransported()), 'ressources pillées')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::LOOTLOST:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Erreur de coordonnées');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId() . '/sftr-3', $commander->getName())
					->addTxt(' n\'a pas attaqué la planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt(' car son joueur est de votre faction ou sous la protection débutant.')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::CONQUEREMPTYSSUCCESS:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Colonisation réussie');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId() . '/sftr-3', $commander->getName())
					->addTxt(' a colonisé la planète rebelle située aux coordonnées ')  
					->addLnk('map/place-' . $this->id , Game::formatCoord($this->xSystem, $this->ySystem, $this->position, $this->rSector) . '.')
					->addBoxResource('xp', '+ ' . Format::number($commander->earnedExperience), 'expérience de l\'officier')
					->addTxt('Votre empire s\'étend, administrez votre ')
					->addLnk('bases/base-' . $this->id, 'nouvelle planète')
					->addTxt('.')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::CONQUEREMPTYFAIL:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Colonisation ratée');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/view-memorial', $commander->getName())
					->addTxt(' est tombé lors de l\'attaque de la planète rebelle située aux coordonnées ')
					->addLnk('map/place-' . $this->id, Game::formatCoord($this->xSystem, $this->ySystem, $this->position, $this->rSector))
					->addTxt('.')
					->addSep()
					->addTxt('Il a désormais rejoint le Mémorial. Que son âme traverse l\'Univers dans la paix.')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::CONQUERPLAYERWHITOUTBATTLESUCCESS:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Conquête réussie');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId() . '/sftr-3', $commander->getName())
					->addTxt(' a conquis la planète non défendue ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $this->rPlayer, $this->playerName)
					->addTxt('.')
					->addSep()
					->addBoxResource('xp', '+ ' . Format::number($commander->earnedExperience), 'expérience de l\'officier')
					->addTxt('Elle est désormais votre, vous pouvez l\'administrer ')
					->addLnk('bases/base-' . $this->id, 'ici')
					->addTxt('.')
					->addEnd();
				ASM::$ntm->add($notif);

				$notif = new Notification();
				$notif->setRPlayer($this->rPlayer);
				$notif->setTitle('Planète conquise');
				$notif->addBeg()
					->addTxt('L\'officier ')
					->addStg($commander->getName())
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $commander->getRPlayer(), $commander->getPlayerName())
					->addTxt(' a conquis votre planète non défendue ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt('.')
					->addSep()
					->addTxt('Impliquez votre faction dans une action punitive envers votre assaillant.')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::CONQUERLOST:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Erreur de coordonnées');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId() . '/sftr-3', $commander->getName())
					->addTxt(' n\'a pas attaqué la planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt(' car le joueur est dans votre faction ou sous la protection débutant.')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::COMEBACK:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Rapport de retour');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId() . '/sftr-3', $commander->getName())
					->addTxt(' est de retour sur votre base ')
					->addLnk('map/place-' . $commander->getRBase(), $commander->getBaseName())
					->addTxt(' et rapporte ')
					->addStg(Format::number($commander->getResourcesTransported()))
					->addTxt(' ressources à vos entrepôts.')
					->addEnd();
				ASM::$ntm->add($notif);
				break;
			
			default: break;
		}
	}

	private function sendNotifForConquest($case, $commander, $reports = array()) {
		$nbrBattle = count($reports);
		switch($case) {
			case self::CONQUERPLAYERWHITBATTLESUCCESS:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Conquête réussie');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/commander-' . $commander->getId() . '/sftr-3', $commander->getName())
					->addTxt(' a conquis la planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $this->rPlayer, $this->playerName)
					->addTxt('.')
					->addSep()
					->addTxt($nbrBattle . Format::addPlural($nbrBattle, ' combats ont eu lieu.', ' seul combat a eu lieu'))
					->addSep()
					->addBoxResource('xp', '+ ' . Format::number($commander->earnedExperience), 'expérience de l\'officier')
					->addTxt('Elle est désormais vôtre, vous pouvez l\'administrer ')
					->addLnk('bases/base-' . $this->id, 'ici')
					->addTxt('.');
				for ($i = 0; $i < $nbrBattle; $i++) {
					$notif->addSep();
					$notif->addLnk('fleet/view-archive/report-' . $reports[$i], 'voir le ' . Format::ordinalNumber($i + 1) . ' rapport');
				}
				$notif->addEnd();
				ASM::$ntm->add($notif);

				$notif = new Notification();
				$notif->setRPlayer($this->rPlayer);
				$notif->setTitle('Planète conquise');
				$notif->addBeg()
					->addTxt('L\'officier ')
					->addStg($commander->getName())
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $commander->getRPlayer(), $commander->getPlayerName())
					->addTxt(' a conquis votre planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt('.')
					->addSep()
					->addTxt($nbrBattle . Format::addPlural($nbrBattle, ' combats ont eu lieu.', ' seul combat a eu lieu'))
					->addSep()
					->addTxt('Impliquez votre faction dans une action punitive envers votre assaillant.');
				for ($i = 0; $i < $nbrBattle; $i++) {
					$notif->addSep();
					$notif->addLnk('fleet/view-archive/report-' . $reports[$i], 'voir le ' . Format::ordinalNumber($i + 1) . ' rapport');
				}
				$notif->addEnd();
				ASM::$ntm->add($notif);
				break;
			case self::CONQUERPLAYERWHITBATTLEFAIL:
				$notif = new Notification();
				$notif->setRPlayer($commander->getRPlayer());
				$notif->setTitle('Conquête ratée');
				$notif->addBeg()
					->addTxt('Votre officier ')
					->addLnk('fleet/view-memorial/', $commander->getName())
					->addTxt(' est tombé lors de la tentive de conquête de la planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $this->rPlayer, $this->playerName)
					->addTxt('.')
					->addSep()
					->addTxt($nbrBattle . Format::addPlural($nbrBattle, ' combats ont eu lieu.', ' seul combat a eu lieu'))
					->addSep()
					->addTxt('Il a désormais rejoint de Mémorial. Que son âme traverse l\'Univers dans la paix.');
				for ($i = 0; $i < $nbrBattle; $i++) {
					$notif->addSep();
					$notif->addLnk('fleet/view-archive/report-' . $reports[$i], 'voir le ' . Format::ordinalNumber($i + 1) . ' rapport');
				}
				$notif->addEnd();
				ASM::$ntm->add($notif);

				$notif = new Notification();
				$notif->setRPlayer($this->rPlayer);
				$notif->setTitle('Rapport de combat');
				$notif->addBeg()
					->addTxt('L\'officier ')
					->addStg($commander->getName())
					->addTxt(' appartenant au joueur ')
					->addLnk('diary/player-' . $commander->getRPlayer(), $commander->getPlayerName())
					->addTxt(' a tenté de conquérir votre planète ')
					->addLnk('map/place-' . $this->id, $this->baseName)
					->addTxt('.')
					->addSep()
					->addTxt($nbrBattle . Format::addPlural($nbrBattle, ' combats ont eu lieu.', ' seul combat a eu lieu'))
					->addSep()
					->addTxt('Vous avez repoussé l\'ennemi avec succès.');
				for ($i = 0; $i < $nbrBattle; $i++) {
					$notif->addSep();
					$notif->addLnk('fleet/view-archive/report-' . $reports[$i], 'voir le ' . Format::ordinalNumber($i + 1) . ' rapport');
				}
				$notif->addEnd();
				ASM::$ntm->add($notif);
				break;

			default: break;
		}
	}

	public function createVirtualCommander() {
		$population = $this->population;
		$vCommander = new Commander();
		$vCommander->id = 0;
		$vCommander->rPlayer = 0;
		$vCommander->name = 'officier rebelle';
		$vCommander->avatar = 't3-c4';
		$vCommander->sexe = 1;
		$vCommander->age = 42;
		$vCommander->statement = 1;
		$vCommander->level = round($this->population / (self::POPMAX / self::LEVELMAXVCOMMANDER));

		$nbrsquadron = round($vCommander->level * ($this->resources / (($this->population + 1) * self::COEFFMAXRESOURCE)));
		if ($nbrsquadron == 0) {
			$nbrsquadron = 1;
		}

		$army = array();
		$squadronsIds = array();

		for ($i = 0; $i < $nbrsquadron; $i++) {
			$aleaNbr = ($this->coefHistory * $this->coefResources * $this->position * $i) % SquadronResource::size();
			$army[] = SquadronResource::get($vCommander->level, $aleaNbr);
			$squadronsIds[] = 0;
		}

		for ($i = $vCommander->level - 1; $i >= $nbrsquadron; $i--) {
			$army[$i] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, Utils::now());
			$squadronsIds[] = 0;
		}

		$vCommander->setSquadronsIds($squadronsIds);
		$vCommander->setArmyInBegin($army);
		$vCommander->setArmy();
		$vCommander->setPevInBegin();
		return $vCommander;
	}
}
?>