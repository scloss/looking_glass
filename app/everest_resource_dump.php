<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $host_name
 * @property string $ip_addr
 * @property string $resource_name
 * @property string $resource_descr
 * @property string $resource_alias
 * @property string $interface_ip
 * @property string $bandwidth_polled
 * @property string $bandwidth_configured
 * @property string $link_name
 * @property string $link_capacity_mbps
 * @property string $poll_status
 * @property string $created_time
 */
class everest_resource_dump extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'everest_resource_dump';

    /**
     * @var array
     */
    protected $fillable = ['host_name', 'ip_addr', 'resource_name', 'resource_descr', 'resource_alias', 'interface_ip', 'bandwidth_polled', 'bandwidth_configured', 'link_name', 'link_capacity_mbps', 'poll_status', 'created_time'];

}
