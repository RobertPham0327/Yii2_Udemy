<?php
/**
 * User: TheCodeholic
 * Date: 4/17/2020
 * Time: 11:56 AM
 */

namespace frontend\controllers;

use Yii;
use common\models\Comment;
use common\models\Video;
use common\models\VideoLike;
use common\models\VideoView;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class VideoController
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package frontend\controllers
 */
class VideoController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['like', 'dislike', 'history'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'like' => ['post'],
                    'dislike' => ['post'],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'main';
        $dataProvider = new ActiveDataProvider([
            'query' => Video::find()->with('createdBy')->published()->latest(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id)
    {
        $this->layout = 'auth';
        $video = $this->findVideo($id);

        $videoView = new VideoView();
        $videoView->video_id = $id;
        $videoView->user_id = Yii::$app->user->id;
        $videoView->created_at = time();
        $videoView->save();

        $similarVideos = Video::find()
            ->published()
            ->byKeyword($video->title)
            ->andWhere(['NOT', ['video_id' => $id]])
            ->limit(10)
            ->all();

        $comments = $video
            ->getComments()
            ->with(['createdBy'])
            ->parent()
            ->latest()
            ->all();

        return $this->render('view', [
            'model' => $video,
            'comments' => $comments,
            'similarVideos' => $similarVideos
        ]);
    }

    public function actionViews($id)
    {
        $video = Video::findOne($id);
        if (!$video) {
            throw new NotFoundHttpException("Video does not exit");
        }

        $videoView = new VideoView();
        $videoView->video_id = $id;
        $videoView->user_id = Yii::$app->user->id;
        $videoView->created_at = time();
        $videoView->save();

        return $this->render('views', [
            'model' => $video
        ]);
    }

    public function actionLike($id)
    {
        $video = $this->findVideo($id);
        $userId = Yii::$app->user->id;

        $videoLikeDislike = VideoLike::find()
            ->userIdVideoId($userId, $id)
            ->one();
        if (!$videoLikeDislike) {
            $this->saveLikeDislike($id, $userId, VideoLike::TYPE_LIKE);
        } else if ($videoLikeDislike->type == VideoLike::TYPE_LIKE) {
            $videoLikeDislike->delete();
        } else {
            $videoLikeDislike->delete();
            $this->saveLikeDislike($id, $userId, VideoLike::TYPE_LIKE);
        }

        return $this->renderAjax('_buttons', [
            'model' => $video
        ]);
    }


    public function actionDislike($id)
    {
        $video = $this->findVideo($id);
        $userId = Yii::$app->user->id;

        $videoLike = VideoLike::find()
            ->userIdVideoId($userId, $id)
            ->one();
        if (!$videoLike) {
            $this->saveLikeDislike($id, $userId, VideoLike::TYPE_DISLIKE);
        } else if ($videoLike->type == VideoLike::TYPE_DISLIKE) {
            $videoLike->delete();
        } else {
            $videoLike->delete();
            $this->saveLikeDislike($id, $userId, VideoLike::TYPE_DISLIKE);
        }

        return $this->renderAjax('_buttons', [
            'model' => $video
        ]);
    }

    public function actionSearch($keyword)
    {

        $this->layout = 'main';
        $query = Video::find()->with('createdBy')->published()->latest();
        if ($keyword) {
            $query->byKeyword($keyword)->orderBy("MATCH(title, description) AGAINST ('$keyword') DESC");
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('search', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionHistory()
    {
        $this->layout = 'main';
        $query = Video::find()
            ->alias('v')
            ->innerJoin("(SELECT video_id, MAX(created_at) as max_date FROM video_view
                    WHERE user_id = :userId
                    GROUP BY video_id) vv", 'vv.video_id = v.video_id', [
                'userId' => Yii::$app->user->id
            ])
            ->orderBy("vv.max_date DESC");

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('history', [
            'dataProvider' => $dataProvider
        ]);
    }

    protected function findVideo($id)
    {
        $video = Video::findOne($id);
        if (!$video) {
            throw new NotFoundHttpException("Video does not exit");
        }

        return $video;
    }

    protected function saveLikeDislike($videoId, $userId, $type)
    {
        $videoLikeDislike = new VideoLike();
        $videoLikeDislike->video_id = $videoId;
        $videoLikeDislike->user_id = $userId;
        $videoLikeDislike->type = $type;
        $videoLikeDislike->created_at = time();
        $videoLikeDislike->save();
    }
}