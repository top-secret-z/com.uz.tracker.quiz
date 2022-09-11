<?php
namespace wcf\system\event\listener;
use wcf\data\package\PackageCache;
use wcf\data\quiz\Quiz;
use wcf\data\user\tracker\log\TrackerLogEditor;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\cache\builder\TrackerCacheBuilder;
use wcf\system\WCF;

/**
 * Listen to Quiz rating action.
 * 
 * @author		2016-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.tracker.quiz
 */
class TrackerQuizRatingListener implements IParameterizedEventListener {
	/**
	 * tracker and link
	 */
	protected $tracker = null;
	protected $link = '';
	
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		if (!MODULE_TRACKER) return;
		
		// only if user is to be tracked
		$user = WCF::getUser();
		if (!$user->userID || !$user->isTracked || WCF::getSession()->getPermission('mod.tracking.noTracking')) return;
		
		// only if trackers
		$trackers = TrackerCacheBuilder::getInstance()->getData();
		if (!isset($trackers[$user->userID])) return;
		
		$this->tracker = $trackers[$user->userID];
		if (!$this->tracker->contentQuiz) return;
		
		// actions / data
		$action = $eventObj->getActionName();
		
		if ($action == 'rate') {
			$params = $eventObj->getParameters();
			$quiz = new Quiz($params['quizID']);
			$this->link = $quiz->getLink();
			$this->store('wcf.uztracker.description.quiz.rating.rate', 'wcf.uztracker.type.content');
		}
		
		if ($action == 'unrate') {
			$params = $eventObj->getParameters();
			$quiz = new Quiz($params['quizID']);
			$this->link = $quiz->getLink();
			$this->store('wcf.uztracker.description.quiz.rating.unrate', 'wcf.uztracker.type.content');
		}
	}
	
	/**
	 * store log entry
	 */
	protected function store ($description, $type, $name = '', $content = '') {
		$packageID = PackageCache::getInstance()->getPackageID('com.uz.tracker.quiz');
		TrackerLogEditor::create(array(
				'description' => $description,
				'link' => $this->link,
				'name' => $name,
				'trackerID' => $this->tracker->trackerID,
				'type' => $type,
				'packageID' => $packageID,
				'content' => $content
		));
	}
}
