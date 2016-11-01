<?php

class UserObserver {

		public function saving($model)
		{
			// if there's a new password, hash it
			if($model->isDirty('password')) {
					$model->password = Hash::make($model->password);
						unset($model->password_confirmation);
			}
		}

		public function deleting($model)
		{
			$model->discussions()->delete();
			$model->comments()->delete();
			$model->my_recommendations()->delete();
			$model->activites()->delete();
			$model->pollAnswers()->delete();
			$model->quizzAnswers()->delete();
			$model->feedbackAnswers()->delete();
		}

}
