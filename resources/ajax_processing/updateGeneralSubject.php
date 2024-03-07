<?php
		$className = $_POST['className'];
		$updateID = $_POST['updateID'];
		$subjectSpecialist = ($_POST['subjectSpecialist'] == "Everyone") ? "" : $_POST['subjectSpecialist'];
		$shortName = trim($_POST['shortName']);

		if ($updateID != ''){
			$instance = new $className(new NamedArguments(array('primaryKey' => $updateID)));
		}else{
			$instance = new $className();
		}

		$instance->shortName = $shortName;
		$instance->subjectSpecialist = $subjectSpecialist;

		// Check to see if the general subject name exists.  If not then save.
		if ($instance->duplicateCheck($shortName, $subjectSpecialist) == 0)  {
			try {
				$instance->save();
			} catch (Exception $e) {

				echo $e->getMessage();
			}
		} else {
			echo _("A duplicate ") . strtolower(preg_replace("/[A-Z]/", " \\0" , lcfirst($className))) . _(" and specialist pairing exists.");
		}

?>
