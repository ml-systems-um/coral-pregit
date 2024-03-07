<?php
		$className = $_GET['className'];
		$updateID = $_GET['updateID'];

		if ($updateID){
			$instance = new $className(new NamedArguments(array('primaryKey' => $updateID)));
		}else{
			$instance = new $className();
		}
?>
		<div id='div_updateForm'>

		<input type='hidden' id='editClassName' value='<?php echo $className; ?>'>
		<input type='hidden' id='editUpdateID' value='<?php echo $updateID; ?>'>

		<div class='formTitle' style='width:245px;'>
			<span class='headerText' style='margin-left:7px;'>
				<?php 
					$editType = ($updateID) ? _("Edit ") : _("Add ");
					echo $editType . preg_replace("/[A-Z]/", " \\0" , $className);
				?>
			</span>
		</div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:250px;">
		<tr>
		<td>

			<table class='noBorder' style='width:200px; margin:10px;'>
			<tr>
				<td>
					<label for="updateVal">Subject Value:</label>
					<input type="text" id="updateVal" value="<?php echo $instance->shortName; ?>" style="width:190px;"/>
				</td>
			</tr>
			<?php if($className == "GeneralSubject"){ ?>
				<?php //Get a list of all users with privileges 1 (Admin) 2 (Edit) or 4 (Subject Specialist)
					$validPrivileges = [1, 2, 4];
					$user = new User();
					$userArray = $user->allAsArray();
					$userOptions = [];
					foreach($userArray as $userInfo){
						$validUser = (in_array($userInfo['privilegeID'], $validPrivileges));
						if($validUser){$userOptions[$userInfo['loginID']] = $userInfo['firstName']." ".$userInfo['lastName'];}
					}					
					asort($userOptions);
					?>
			<tr>
				<td>
					<label for="subjectSpecialist">Subject Specialist:</label>
					<select id="subjectSpecialist" name="subjectSpecialist">
						<option value="Everyone">-Everyone-</option>
						<?php 
						//Present a list of all Subject Specialists, Editors, and Admins sorted by name.
							foreach($userOptions as $value=>$option){
								$selected = ($value == $instance->subjectSpecialist) ? "selected" : "";
								echo "<option value='{$value}' {$selected}>{$option}</option>";
							}
						?>
					</select>
				</td>
			</tr>
			<?php } ?>
			</table>

		</td>
		</tr>
		</table>

		<br />
		<table class='noBorderTable' style='width:125px;'>
			<tr>

				<td style='text-align:left'><input type='button' value='<?php echo _("submit");?>' name='submitGeneralSubjectForm' id ='submitGeneralSubjectForm' class='submit-button'></td>
				<td style='text-align:right'><input type='button' value='<?php echo _("cancel");?>' onclick="window.parent.tb_remove(); return false;" class='cancel-button'></td>
			</tr>
		</table>


		</form>
		</div>

		<script type="text/javascript" src="js/forms/generalSubjectForm.js?random=<?php echo rand(); ?>"></script>
