<?php

namespace ride\cli\command\orm;

use ride\cli\command\AbstractCommand;

use ride\library\orm\OrmManager;

use \Exception;

/**
 * Command to calculate the average scores of evaluations
 */
class SurveyEvaluationCalculateAverageCommand extends AbstractCommand {

    /**
     * Initializes the command
     * @return null
     */
    protected function initialize() {
        $this->setDescription('Calculate the average score of survey evaluations');

        $this->addArgument('evaluation', 'Id of an evaluation', false);
    }

    /**
     * Executes the command
     * @param ride\library\orm\OrmManager $orm
     * @param string $evaluation Id of a survey evaluation
     * @return null
     */
    public function invoke(OrmManager $orm, $evaluation = null) {
        $model = $orm->getSurveyEvaluationModel();
        $id = $evaluation;

        if (!$evaluation) {
            $evaluations = $model->calculateAverageScores();
        } else {
            $evaluation = $model->getById($id);
            if (!$evaluation) {
                throw new Exception('Could not calculate average score: no evaluation found with id ' . $id);
            }

            $model->calculateAverageScore($evaluation);

            $evaluations = array($evaluation);
        }

        foreach ($evaluations as $evaluation) {
            $this->output->writeLine('- ' . $evaluation->getName() . ': ' . $evaluation->getAverageScore());
        }
    }

}
