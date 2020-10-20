<?php
/**
 * PhpStorm.
 * User: Jay
 * Date: 2018/5/2
 */

namespace PHPZlc\Document\Entrance\DocumentBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDocumentConfigCommand extends Base
{

    public function configure()
    {
        $this
            ->setName($this->command_pre . 'generate:document:config')
            ->setDescription($this->description_pre . '生成API全局配置文件')
            ->setHelp(<<<EOF
<info>%command.name%</info> 生成API文档全局配置文件
        <comment>[注意]这是一个一次性命令,会生成默认的配置文件[app/config/{$this->document_config}]，如需变更在其基础修改即可</comment>
EOF
            );
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->title($this->getName());

        $globalApiConfigSaveDir = $this->getRootPath() . '/app/config/';
        $globalApiConfigPath = $globalApiConfigSaveDir . $this->document_config;

        if(is_file($globalApiConfigPath)){
            $this->io->error($this->document_config . '已存在');
            return false;
        }

        $content_vars = array(
            '$' => '$',
        );

        $content = <<<FOE
<?php
/**
 * 全局API文档配置文件
 *
 */

/**
 * @var string 标题
 */
{$content_vars['$']}title = '';

/**
 * @var string 出版商
 */
{$content_vars['$']}publishing = '';

/**
 * @var string 说明
 */
{$content_vars['$']}explain = '';

/**
 * @var string 注意
 */
{$content_vars['$']}note = '';

/**
 * @var string 附录
 */
{$content_vars['$']}appendix = '';

include 'document_local.config.php';

FOE;

        file_put_contents($globalApiConfigPath, $content);

        $content = <<<EOF
<?php
/**
 * 全局API文档本地配置文件
 *
 */

/**
 * @var string 接口根域名  例如  https://www.****.com 或者 http://www.****.com
 */
{$content_vars['$']}domain = '';

/**
 * @var string 数据库地址
 */
{$content_vars['$']}database_host = 'localhost';

/**
 * @var string 数据库名称
 */
{$content_vars['$']}database_name = '';

/**
 * @var string 数据库用户名
 */
{$content_vars['$']}database_user_name = '';

/**
 * @var string 数据库登陆密码
 */
{$content_vars['$']}database_password = '';

EOF;

        file_put_contents($globalApiConfigSaveDir . 'document_local.config.php', $content);
        file_put_contents($globalApiConfigSaveDir . 'document_local.config.php.ref', $content);

        $this->io->success($this->document_config . '创建成功');

        return true;
    }



}