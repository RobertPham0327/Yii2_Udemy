<?php

namespace common\models;

use common\models\Image;
use common\models\Testimonial;
use Yii;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $name
 * @property string $base_url
 * @property string $path_url
 * @property string $mine_type
 *
 * @property Image[] $images
 * @property Testimonial[] $testimonials
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'path_url', 'base_url', 'mine_type'], 'required'],
            [['name', 'path_url', 'base_url', 'mine_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'path_url' => Yii::t('app', 'Path Url'),
            'base_url' => Yii::t('app', 'Base Url'),
            'mine_type' => Yii::t('app', 'Mine Type'),
        ];
    }

    /**
     * Gets query for [[Images]].
     *
     * @return \yii\db\ActiveQuery|ImageQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::class, ['file_id' => 'id']);
    }

    /**
     * Gets query for [[Testimonials]].
     *
     * @return \yii\db\ActiveQuery|TestimonialQuery
     */
    public function getTestimonials()
    {
        return $this->hasMany(Testimonial::class, ['customer_image_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return FileQuery the active query used by this AR class.
     */
    // public static function find()
    // {
    //     return new FileQuery(get_called_class());
    // }

    public function absoluteUrl()
    {
        return $this->base_url . '/' . $this->name;
    }

    // public function afterDelete()
    // {
    //     parent::afterDelete();
    //     unlink($this->path_url . '/' . $this->name);
    // }

    public function afterDelete()
    {
        parent::afterDelete();

        $filePath = $this->path_url . '/' . $this->name;

        // Check if the file path is a URL
        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            // Perform an HTTP DELETE request
            $ch = curl_init($filePath);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                Yii::info("Remote file deleted successfully: $filePath");
            } else {
                Yii::error("Failed to delete remote file: $filePath");
            }
        } else {
            // Local file deletion
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    Yii::info("Local file deleted successfully: $filePath");
                } else {
                    Yii::error("Failed to delete local file: $filePath");
                }
            } else {
                Yii::error("File does not exist: $filePath");
            }
        }
    }
}
