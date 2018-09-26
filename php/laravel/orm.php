<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Student extends Model{
    //指定表名
    protected $table= 'vipinfo';
    //指定主键
    protected $primaryKey= 'vip_ID';
    /**
     * created_at 和 updated_at
     * 不需要时间戳
     */
    public $timestamps = false;

    protected function getDateFormat(){
        return time();
    }
}
