# **MSSQL备份工具** (备份 / 压缩 / 自动清理)
基于PHP的MSSQL自动化备份工具，可以一条龙的进行备份，压缩备份，清理旧备份。

## 使用说明
虽然代码是PHP的，但是限制只能以cli(命令行)的方式运行，同时需要启动php-mssql扩展。  
因为PHP 5.3及以上版本取消了对php-mssql扩展的支持，所以推荐使用PHP 5.2.17运行此脚本。  
备份的过程会自动忽略系统表（`master, model, msdb, Northwind, pubs, tempdb`），如果需要备份系统表或者排除其他用户表，请编辑源代码`$sys_dblist`参数进行设置。  
压缩的过程可以使用WinRAR(rar.exe)或者7Zip(7z.exe)，不过最终都是输出zip文件。 
这个工具在 MSSQL 2000 以及 MSSQL 2005 上测试通过。

## 设置说明
用记事本打开源代码后，只需要修改前面几行

> `MSSQL_HOST` MSSQL服务器地址，通常为localhost  
> `MSSQL_USER` MSSQL用户名，需要有读取master表以及备份数据库的权限  
> `MSSQL_PASS` MSSQL密码  
> `TARGET_PATH` 备份目的地，需要你先把目录创建好，否则会提示没有写权限错误  
> `COMPRESS_TOOL_PATH` 压缩工具的路径，可以是WinRAR(rar.exe)或者7Zip(7z.exe)，建议写绝对路径  

设置完成后使用计划任务在每天服务器空闲的时候运行一次就行了，最终的保存文件会以`mssql_日期.zip`为名称进行保存
同时会自动清理一个月前的备份文件

如果在使用时遇到了任何问题，欢迎和我交流：  
> QQ: 565837499  
> Email: <vibbow@gmail.com>  
> Blog: <http://vsean.net/>


------

# **MSSQL Backup Tool** (Backup / Compress / Auto Clean)
An PHP based MSSQL backup tool, can backup database, compress back, autoclean old backup in single process.

## How to use it
The code is based on PHP, but only can run in command line envirement. Also you need to enable the php-mssql extension.  
Because php-mssql extension only exists in PHP 5.2, so I suggest use PHP 5.2.17 to run this script.  
During the backup, the script will auto ignore the system database（`master, model, msdb, Northwind, pubs, tempdb`）. If you want to backup system database or ignore other database, please change the `$sys_dblist` in source code.  
The compress tool can use WinRAR(rar.exe) or 7Zip(7z.exe), but will finally output as .zip file.

## Configure
Use any editor to open the source code, and change those settings:

> `MSSQL_HOST` MSSQL Server address, usually is localhost  
> `MSSQL_USER` MSSQL Login username, Need to have permission to read 'master' table and backup database  
> `MSSQL_PASS` MSSQL Login Password  
> `TARGET_PATH` Target directory to backup, you need to create this directory before backup  
> `COMPRESS_TOOL_PATH` The path of compress tool, can use WinRAR(rar.exe) or 7Zip(7z.exe), Suggest to use absolute path  

Then use windows schedule task to run this once a day (Otherwise the new backup will overwrite the old backup). The final backup file will saved like this name: `mssql_[date].zip`.  
Also this tool will clean the old backup older than 1 month.

If you got any problem of this code, please contact me at <vibbow@gmail.com>.