<?php
/**
 * 这是 Hankin 的一套主题
 *
 * @package Hankin
 * @author Hankin
 * @version 1.0
 * @link https://www.hanhanjun.com
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<?php
$GLOBALS['z'] = $this->options->CDNURL;
$GLOBALS['timechoice'] = $this->options->langis;
function threadedComments($comments, $options)
{
    $commentClass = '';
    if ($comments->authorId)
    {
        if ($comments->authorId == $comments->ownerId)
        {
            $commentClass .= ' comment-by-author';  //如果是文章作者的评论添加 .comment-by-author 样式
        }
        else
        {
            $commentClass .= ' comment-by-user';  //如果是评论作者的添加 .comment-by-user 样式
        }
    }
    $commentLevelClass = $comments->_levels > 0 ? ' comment-child' : ' comment-parent';  //评论层数大于0为子级，否则是父级
    $depth = $comments->levels + 1; //添加的一句
    ?>

    <!--自定义评论代码结构-->
    <!--<a name="<?php //$comments->theId(); ?>" class="target">
</a>-->
    <li data-no-instant id="<?php $comments->theId(); ?>" style="list-style: none" class="comment-body<?php
    if ($depth > 1 && $depth < 3)
    {
        echo ' comment-child ';
        $comments->levelsAlt('comment-level-odd', ' comment-level-even');
    }
    else if ($depth > 2)
    {
        echo ' comment-child2';
        $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
    }
    else
    {
        echo ' comment-parent';
    }
    $comments->alt(' comment-odd', ' comment-even');
    echo $commentClass;
    ?>">
        <div id="div-<?php $comments->theId(); ?>" class="comment-body">
            <?php
            //头像CDN by Rich http://forum.typecho.org/viewtopic.php?f=5&t=6165&p=29961&hilit=gravatar#p29961
            $host = $GLOBALS['z'];//自定义头像CDN服务器
            $url = '/avatar/';//自定义头像目录,一般保持默认即可
            $size = '80';//自定义头像大小
            $rating = Helper::options()->commentsAvatarRating;
            $hash = md5(strtolower($comments->mail));
            $avatar = $host . $url . $hash . '?s=' . $size . '&r=' . $rating . '&d=';
            ?>
            <a class="pull-left thumb-sm" style="margin:5px">
                <img data-original="<?php echo $avatar ?>" src="<?php Helper::options()->themeUrl('img/a'.rand(1,9).'.jpg'); ?>"
                     class="lazy avatar-40 photo img-circle" height="40" width="40">
            </a>
            <div class="m-b m-l-xxl">
                <div class="comment-meta">
            <span class="comment-author vcard">
              <b class="fn"><?php $comments->author(); ?></b>
              </span>
                    <div class="comment-metadata">
                        <time class="text-muted text-xs block m-t-xs" pubdate="pubdate"
                              datetime="<?php $comments->date(); ?>"><?php if ($GLOBALS['timechoice'] == '0'): ?><?php $comments->date("F jS, Y \a\t h:i a"); ?><?php elseif ($GLOBALS['timechoice'] == '1'): ?><?php $comments->date('Y 年 m 月 d 日 h 时 i 分 A'); ?><?php elseif ($GLOBALS['timechoice'] == '2'): ?><?php $comments->date('Y 年 m 月 d 日 h 时 i 分 A'); ?><?php endif; ?>
                        </time>
                    </div>
                </div>
                <!--回复内容-->
                <div class="comment-content m-t-sm">
                    <p><b><?php get_comment_at($comments->coid) ?></b> <?php get_filtered_comment($comments->coid) ?>
                    </p>
                </div>
                <!--回复按钮-->
                <div class="reply m-t-sm">
                    <?php $comments->reply(); ?>
                </div>
            </div>

        </div>
        <!-- 单条评论者信息及内容 -->
        <?php if ($comments->children) { ?> <!-- 是否嵌套评论判断开始 -->
            <div class="children list-unstyled m-l-xxl">
                <?php $comments->threadedComments($options); ?> <!-- 嵌套评论所有内容-->
            </div>
        <?php } ?> <!-- 是否嵌套评论判断结束 -->
    </li><!--匹配`自定义评论的代码结构`下面的li标签-->
<?php } ?>


<div id="comments">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
        <h4 class="comments-title m-t-lg m-b"><?php $this->commentsNum(_t(' 暂无评论'), _t(' 1 条评论'), _t(' %d 条评论')); ?></h4>
    <?php $comments->listComments(); ?>
        <nav class="text-center m-t-lg m-b-lg" role="navigation">
            <?php $comments->pageNav('&lt;', '&gt;'); ?>
        </nav>

    <?php endif; ?>
    <?php //endif; ?>
    <!--如果允许评论，会出现评论框和个人信息的填写-->
    <?php if ($this->allow('comment')): ?>
        <div id="<?php $this->respondId(); ?>" class="respond comment-respond">

            <h4 id="reply-title" class="comment-reply-title m-t-lg m-b">发表评论
                <small class="cancel-comment-reply">
                    <?php $comments->cancelReply(); ?>
                </small>
            </h4>
            <form id="comment_form" action="<?php $this->commentUrl() ?>" method="post" class="comment-form"
                  role="form">
                <div class="comment-form-comment form-group">
                    <label for="comment">评论
                        <span class="required text-danger">*</span></label>
                    <textarea id="comment" class="textarea form-control OwO-textarea" name="text" rows="5"
                              maxlength="65525" required><?php $this->remember('text'); ?></textarea>
                    <div class="OwO"></div>
                </div>
                <!--判断是否登录-->
                <?php if ($this->user->hasLogin()): ?>
                    <p>欢迎 <a data-no-intant target="blank"
                             href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a> 归来！
                        <a data-no-instant href="<?php $this->options->logoutUrl(); ?>" title="Logout">退出&raquo;</a></p>
                <?php else: ?>
                <?php if ($this->remember('author', TRUE) != "" && $this->remember('mail', TRUE) != "") : ?>
                <p>欢迎 <a style="color: #333!important;"
                         onClick='showhidediv("author_info")'><?php $this->remember('author'); ?></a> 归来！</p>
                <div id="author_info" style="display:none;">
                    <?php else : ?>
                    <div id="author_info" class="row row-sm">
                        <?php endif; ?>
                        <div class="comment-form-author form-group col-sm-6 col-md-4">
                            <label for="author">名称
                                <span class="required text-danger">*</span></label>
                            <input id="author" class="form-control" name="author" type="text"
                                   value="<?php $this->remember('author'); ?>" maxlength="245" placeholder="姓名或昵称"
                                   required>
                        </div>

                        <div class="comment-form-email form-group col-sm-6 col-md-4">
                            <label for="email">邮箱
                                <span class="required text-danger">*</span>
                            </label>
                            <input type="email" name="mail" id="mail" class="form-control" required
                                   placeholder="邮箱 (必填,将保密)" value="<?php $this->remember('mail'); ?>"/>
                        </div>

                        <div class="comment-form-url form-group col-sm-12 col-md-4">
                            <label for="url">网址</label>
                            <input id="url" class="form-control" name="url" type="url"
                                   value="<?php $this->remember('url'); ?>" maxlength="200" placeholder="网站或博客"></div>
                    </div>
                    <?php endif; ?>
                    <!--提交按钮-->
                    <div class="form-group">
                        <button type="submit" name="submit" id="submit" class="submit btn btn-success padder-lg">
                            <span class="text">发表评论</span>
                        </button>
                        <i class="icon-spin iconfont icon-spinner hide" id="spin"></i>
                        <input type="hidden" name="comment_post_ID" value="448" id="comment_post_ID">
                        <input type="hidden" name="comment_parent" id="comment_parent" value="0">
                    </div>
            </form>
        </div>
    <?php else: ?>
        <h4>此处评论已关闭</h4>
    <?php endif; ?>
</div>
<script type="text/javascript">
    function showhidediv(id) {
        var sbtitle = document.getElementById(id);
        if (sbtitle) {
            if (sbtitle.style.display == 'block') {
                sbtitle.style.display = 'none';
            } else {
                sbtitle.style.display = 'block';
            }
        }
    }

    (function () {
        window.TypechoComment = {
            dom: function (id) {
                return document.getElementById(id);
            },
            create: function (tag, attr) {
                var el = document.createElement(tag);
                for (var key in attr) {
                    el.setAttribute(key, attr[key]);
                }
                return el;
            },
            reply: function (cid, coid) {
                var comment = this.dom(cid),
                    parent = comment.parentNode,
                    response = this.dom('<?php echo $this->respondId(); ?>'),
                    input = this.dom('comment-parent'),
                    form = 'form' == response.tagName ? response : response.getElementsByTagName('form')[0],
                    textarea = response.getElementsByTagName('textarea')[0];
                if (null == input) {
                    input = this.create('input', {
                        'type': 'hidden',
                        'name': 'parent',
                        'id': 'comment-parent'
                    });
                    form.appendChild(input);
                }
                input.setAttribute('value', coid);
                if (null == this.dom('comment-form-place-holder')) {
                    var holder = this.create('div', {
                        'id': 'comment-form-place-holder'
                    });
                    response.parentNode.insertBefore(holder, response);
                }
                comment.appendChild(response);
                this.dom('cancel-comment-reply-link').style.display = '';
                if (null != textarea && 'text' == textarea.name) {
                    textarea.focus();
                }
                return false;
            },
            cancelReply: function () {
                var response = this.dom('<?php echo $this->respondId(); ?>'),
                    holder = this.dom('comment-form-place-holder'),
                    input = this.dom('comment-parent');
                if (null != input) {
                    input.parentNode.removeChild(input);
                }
                if (null == holder) {
                    return true;
                }
                this.dom('cancel-comment-reply-link').style.display = 'none';
                holder.parentNode.insertBefore(response, holder);
                return false;
            }
        };
    })();
</script>
