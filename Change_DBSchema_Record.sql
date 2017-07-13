# ------------------------------------
# tp_goods_category表字段更改SQL语句
# ------------------------------------
alter table tp_goods_category add column new_id varchar(20) null default null comment '新分类id' after `level`;
alter table tp_goods_category add column new_pid varchar(20) null default null comment '新分类父id' after new_id;
alter table tp_goods_category add column new_sortpath varchar(50) null default null comment '新分类排序字段' after new_pid;

-- 更新字段new_id值
update tp_goods_category c set c.new_id=case
    when c.id <10 then concat('000',c.id)
    when c.id <100 and c.id >=10 then concat('00',c.id)
    when c.id <1000 and c.id >=100 then concat('0',c.id)
    else null end;

-- 更新字段new_pid值
update tp_goods_category c set c.new_pid=case
    when c.parent_id = 0 then '0000'
    when c.parent_id <10 and c.parent_id >0 then concat('000',c.parent_id)
    when c.parent_id <100 and c.parent_id >=10 then concat('00',c.parent_id)
    when c.parent_id <1000 and c.parent_id >=100 then concat('0',c.parent_id)
    else null end;

-- 更新字段new_sortpath值
update tp_goods_category c set c.new_sortpath=case
    when c.`level` = 1 then concat('0000_',c.new_id)
    when c.`level` = 2 then concat('0000_',c.new_pid,'_',c.new_id)
    else '' end;

update tp_goods_category tar inner join (
    select c.id,concat(big.new_sortpath,'_',c.new_id)as new_sortpath from tp_goods_category c,
        (select * from tp_goods_category gc where gc.`level` in (2,3)) big where c.parent_id=big.id
    ) cc on tar.id=cc.id set tar.new_sortpath=cc.new_sortpath;

-- 更新后的查询语句
select c.id,c.name,c.parent_id,c.parent_id_path,c.new_id,c.new_pid,c.new_sortpath
from tp_goods_category c order by new_sortpath;
# ------------------------------------
# tp_goods表字段更改SQL语句
# ------------------------------------
alter table tp_goods DROP COLUMN extend_cat_id;--  删除这个什么扩展字段，没用！
alter table tp_goods add cat_level_2 int(11) unsigned null default 0 comment '商品二级分类ID' after cat_id ;
alter table tp_goods add cat_level_1 int(11) unsigned null default 0 comment '商品一级分类ID' after cat_level_2;
-- 更新字段cat_level_2和cat_level_1
update tp_goods gd inner join (
  select g.goods_id,g.cat_id,c.parent_id as cat_level_2
    ,substring(substring_index(c.parent_id_path,'_',2),3,3)as cat_level_1
  from tp_goods g,tp_goods_category c where g.cat_id=c.id
-- order by c.new_sortpath
) gc on gd.goods_id=gc.goods_id set gd.cat_level_2=gc.cat_level_2,gd.cat_level_1=gc.cat_level_1;
-- 更新后的查询语句
select g.goods_id,g.goods_name,g.cat_id,g.cat_level_2,g.cat_level_1
from tp_goods g,tp_goods_category c where g.cat_id=c.id ;
-- order by c.new_sortpath;
# ------------------------------------
# tp_users表字段更改SQL语句
# ------------------------------------
ALTER TABLE `tp_users`
    CHANGE COLUMN `password` `password` VARCHAR(255) NULL COMMENT '密码' AFTER `email`,
    CHANGE COLUMN `qq` `qq` VARCHAR(20) NULL COMMENT 'QQ' AFTER `last_ip`,
    CHANGE COLUMN `mobile` `mobile` VARCHAR(20) NULL COMMENT '手机号码' AFTER `qq`;
# ------------------------------------
# tp_user_address表字段更改SQL语句
# ------------------------------------
ALTER TABLE tp_user_address ADD province_name VARCHAR(50) NULL COMMENT '省份名称' AFTER province;
ALTER TABLE tp_user_address ADD city_name VARCHAR(50) NULL COMMENT '城市名称' AFTER city;
ALTER TABLE `tp_user_address`
    CHANGE COLUMN `district` `area` INT(11) NOT NULL DEFAULT '0' COMMENT '地区' AFTER `city_name`;
ALTER TABLE tp_user_address ADD area_name VARCHAR(50) NULL COMMENT '地区名称' AFTER area;
ALTER TABLE tp_user_address
    CHANGE COLUMN `twon` `town` INT(11) NULL DEFAULT '0' COMMENT '乡镇';
ALTER TABLE tp_user_address ADD town_name VARCHAR(50) NULL COMMENT '乡镇街道名称' AFTER town;
ALTER TABLE `tp_user_address`
    CHANGE COLUMN `country` `country` TINYINT(3) NULL DEFAULT '0' COMMENT '国家' AFTER `email`;
ALTER TABLE `tp_user_address`
    CHANGE COLUMN `zipcode` `zipcode` VARCHAR(6) NULL DEFAULT '' COMMENT '邮政编码' AFTER `address`;
# ------------------------------------
# tp_region表字段更改SQL语句
# ------------------------------------
ALTER  TABLE tp_region ADD COLUMN is_show TINYINT(1) NOT NULL DEFAULT 1 COMMENT '是否显示';
UPDATE tp_region SET name='市辖县' where id in (300,10779,32380);
update tp_region tar inner join (
    select c.id,c.level,c.name,c.is_show from tp_region c,
        (select * from tp_region gc where gc.`level` in (2,3)) big where c.parent_id=big.id
        and (c.name like '%市辖县' or c.name like '%市辖区')
    ) cc on tar.id=cc.id set tar.is_show=0;


# ------------------------------------
# 修改表tp_navigation表数据
# ------------------------------------
TRUNCATE `tp_navigation`;
ALTER TABLE `tp_navigation` AUTO_INCREMENT=1;
INSERT INTO `tp_navigation` ( `name`, `is_show`, `is_new`, `sort`, `url`) VALUES
    ( '手机城', 1, 0, 99, '/home/goods/goodslist/1'),
    ( '家电城', 1, 0, 98, '/home/goods/goodslist/20'),
    ( '珠宝', 1, 0, 97, '/home/goods/goodslist/51'),
    ( '优衣库', 1, 0, 96, '/home/goods/goodslist/5'),
    ( '化妆品', 1, 0, 95, '/home/goods/goodslist/45'),
    ( '数码城', 1, 0, 94, '/home/goods/goodslist/3'),
    ( '户外运动', 1, 0, 93, '/home/goods/goodslist/8'),
    ( '汽车用品', 1, 0, 92, '/home/goods/goodslist/9');






























