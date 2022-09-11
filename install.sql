-- Add cloumns to Tracker
ALTER TABLE wcf1_user_tracker ADD contentQuiz TINYINT(1) NOT NULL DEFAULT 1;

INSERT INTO	wcf1_user_tracker_page (class, page, isPublic) VALUES ('wcf\\page\\QuizFeedPage', 'rssFeedQuiz', 1);
INSERT INTO	wcf1_user_tracker_page (class, page, isPublic) VALUES ('wcf\\form\\QuizQuestionAddForm', 'quizQuestionAdd', 1);
INSERT INTO	wcf1_user_tracker_page (class, page, isPublic) VALUES ('wcf\\form\\QuizQuestionEditForm', 'quizQuestionEdit', 1);
