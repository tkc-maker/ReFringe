<?php
class questions_answers_ui{
    /**   Displays all selected questions and answers for a proposal, where each group is headed by 
     * its "Question_class_name". This method calls "displaySelectedQuestionsAnswers(...)"
     * for each "questions_answers" block. Upon success, a list of questions and editable answers,
     * where each "questions_answers" block is headed by its "Question_classes_name",
     * is displayed. If the input parameter is empty, the '' is returned.
     * 
     * @param JSON assoc. array $jsonAllSelectedQuestionsAnswers -- The passed-in JSON
     *    array has the following format:
     *
     *        '[{“Question_classes_ID”: <id>, 
     *          "Question_classes_name: <string>,
     *          “questions_answers”: [{“question”: “<question>”, “answer”: “<answer>”}, . . .]},
     *          {. . .}, 
     *         . . .]'
     */
    public static function displaySelectedQuestionsAnswersForSelectedQuestionClasses($jsonAllSelectedQuestionsAnswers){
        $results = '';
        if(!empty($jsonAllSelectedQuestionsAnswers)){
            $aryAllSelectedQuestionsAnswers = json_decode($jsonAllSelectedQuestionsAnswers, true);
            $Question_classes_ID = '';
            $results .= "<br><div id=\"divSave_1\" class=\"divSaveAnswers\">Do Answer Validation?: <input type=\"checkbox\" id=\"chkValidate_1\" class=\"chkDoValidation\" checked/><br>".
                    "<input type=\"button\" id=\"btnSave_1\" class=\"btnSaveAnswers\" value=\"Save Answers\"/></div>";
            foreach($aryAllSelectedQuestionsAnswers as $row){
                if($row['Question_classes_ID'] !== $Question_classes_ID){
                    $Question_classes_ID = $row['Question_classes_ID'];
                    $Question_classes_name = $row['Question_classes_name'];
                    $results .= "<p class=\"Question_classes_name\">$Question_classes_name</p>";
                }
                $aryQuestionsAnswers = json_decode($row['questions_answers'], true);
                $results .= self::displaySelectedQuestionsAnswers($Question_classes_ID, $aryQuestionsAnswers);
            }
            $results .= "<div id=\"divSave_2\" class=\"divSaveAnswers\">Do Answer Validation?: <input type=\"checkbox\" id=\"chkValidate_2\" class=\"chkDoValidation\" checked/><br>".
                    "<input type=\"button\" id=\"btnSave_2\" class=\"btnSaveAnswers\" value=\"Save Answers\"/></div>";
        }
        return $results;
    }
    
    /**   Displays all questions and answers for the Quick Easy No Login Proposal heading.
     * This method calls "displaySelectedQuestionsAnswers(...)" for each "questionsAnswers" block. 
     * Upon success, a list of questions and editable answers is displayed. 
     * If the input parameter is empty, '' is returned.
     * 
     * @param type $jsonQuestionsAnswers -- The passed-in JSON
     *    array has the following format:
     *
     *        '[{“Question_classes_ID”: <id>, 
     *          "Question_classes_name: <string>,
     *          “questionsAnswers”: [{“question”: “<question>”, “answer”: “<answer>”}, . . .]},
     *          {. . .}, 
     *         . . .]'
     * @return string -- The contents of the input formatted as HTML.
     */
    public static function displayQuestionsAnswersForNoLoginProposal($jsonQuestionsAnswers){
        $results = "";
        if(!empty($jsonQuestionsAnswers)){
            $aryAllSelectedQuestionsAnswers = json_decode($jsonQuestionsAnswers, true);
            $Question_classes_ID = '';
            $Question_classes_name = $aryAllSelectedQuestionsAnswers[0]['Question_classes_name'];
            $results .= "<div class=\"Question_classes_name\">$Question_classes_name:<br>".
                    "<span id=\"Note\">Note: If you wish to edit your responses in the future, please login with your ".
                    "Last Name as Username and your Email as Password.</span><br>".
                    "<input type=\"button\" id=\"btnHelp\" value=\"Help\"/><br>&nbsp;</div>";
            $results .= "<div id=\"divSave_1\" class=\"divSaveAnswers\"><input type=\"button\" id=\"btnSave_1\" class=\"btnSaveAnswers\" value=\"Save Answers\"/></div>";
            foreach($aryAllSelectedQuestionsAnswers as $row){
                if($row['Question_classes_ID'] !== $Question_classes_ID){
                    $Question_classes_ID = $row['Question_classes_ID'];
                }
                $aryQuestionsAnswers = json_decode($row['questionsAnswers'], true);
                $results .= self::displaySelectedQuestionsAnswers($Question_classes_ID, $aryQuestionsAnswers);
            }
            $results .= "<div id=\"divSave_2\" class=\"divSaveAnswers\"><input type=\"button\" id=\"btnSave_2\" class=\"btnSaveAnswers\" value=\"Save Answers\"/></div>";
        }
        return $results;
    }
    
    /**   Displays all questions and answers for the New Proposal screen, based upon the
     * "Quick Easy No Login Proposal". This method calls "displaySelectedQuestionsAnswers(...)" 
     * for each "questionsAnswers" block. Upon success, a list of questions and editable 
     * answers is displayed. If the input parameter is empty, '' is returned.
     * 
     * @param type $jsonQuestionsAnswers -- The passed-in JSON
     *    array has the following format:
     *
     *        '[{“Question_classes_ID”: <id>, 
     *          "Question_classes_name: <string>,
     *          “questionsAnswers”: [{“question”: “<question>”, “answer”: “<answer>”}, . . .]},
     *          {. . .}, 
     *         . . .]'
     * @return string -- The contents of the input formatted as HTML.
     */
    public static function displayQuestionsAnswersForNewProposal($jsonQuestionsAnswers){
        $results = "";
        if(!empty($jsonQuestionsAnswers)){
            $aryAllSelectedQuestionsAnswers = json_decode($jsonQuestionsAnswers, true);
            $Question_classes_ID = '';
            $results .= "<div class=\"PageHeading\">Enter a New Proposal:<br>".
               "<input type=\"button\" id=\"btnHelp\" value=\"Help\"/><br>&nbsp;</div>";
            $results .= "<div id=\"divSave_1\" class=\"divSaveAnswers\"><input type=\"button\" id=\"btnSave_1\" class=\"btnSaveAnswers\" value=\"Save Answers\"/></div>";
            foreach($aryAllSelectedQuestionsAnswers as $row){
                if($row['Question_classes_ID'] !== $Question_classes_ID){
                    $Question_classes_ID = $row['Question_classes_ID'];
                }
                $aryQuestionsAnswers = json_decode($row['questionsAnswers'], true);
                $results .= self::displaySelectedQuestionsAnswers($Question_classes_ID, $aryQuestionsAnswers);
            }
            $results .= "<div id=\"divSave_2\" class=\"divSaveAnswers\"><input type=\"button\" id=\"btnSave_2\" class=\"btnSaveAnswers\" value=\"Save Answers\"/></div>";
        }
        return $results;
    }
    
    /**   Takes the associative array passed in, and converts it into a column of rows,
     * where read-only questions are above TextAreas, representing answers. Also,
     * a "Question_classes_ID" is passed in to be carried by the "question_row" div.
     * If an empty parameter is passed in, then '' is returned. Also, to the right 
     * of each answer TextArea is a table cell for a possible error-message.
     * 
     * @param integer $Question_classes_ID
     * @param Assoc Array $aryQuestionsAnswers
     * @return string -- a column of rows of questions/answers.
     */
    public static function displaySelectedQuestionsAnswers($Question_classes_ID, $aryQuestionsAnswers) {    
        $results = '';
        if(!empty($Question_classes_ID) && !empty($aryQuestionsAnswers)){
            foreach($aryQuestionsAnswers as $row){
                $question = $row['question'];
                self::tagValidationCode($question);
                $results .= "<div class=\"question_answer_block\">".
                    "<div class=\"question_row\" data-Question_classes_ID=\"$Question_classes_ID\">".$question."</div>".
                    "<div class=\"tdErrorMessage\"></div>".    
                    "<textarea class=\"answer_box\">".($row['answer'])."</textarea></div>";
            }
            return $results;
        }else{
            return '';
        }
    }
    
    /**   Places <span class="validation_code">...</span> tags around the
     * validation code in the passed-in question, if one exists. Returns
     * the question by side-effect.
     * 
     * @param string $question
     */
    public static function tagValidationCode(&$question){
        $asterisk = '';
        $aryQuestion = explode("::", $question);
        
        if(count($aryQuestion) > 1){
            $rule = $aryQuestion[0]."::";
            if(str_contains($rule, '*')){
                $asterisk = '*';
                $rule = str_replace('*', '', $rule);
            }
            $question = $asterisk.'<span class="validation_code">'.trim($rule).'</span>'.trim($aryQuestion[1]);
        }
    }
    
}//End class, "questions_answers_ui".