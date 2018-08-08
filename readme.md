## Что, действительно кому-то заказывал ? xDD

# Тестовое задание Star Tour

На базе Laravel с интерфейсом на bootstrap сделать скрипт, который бы парсил удаленный сайт, предварительно обработав сохранял информацию в базе и показывал на странице.

Как это должно работать со стороны пользователи:

На главной странице имеется поле, в которое пользователь вносит текст и кнопка отправить..
Текст может быть любым и может включать одно или несколько url. После url в тексте будет размещены одна или несколько вставок типа
class=”class_name” или id=”id_name” 
что является идентификатором блока 

Например, текст:
>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's https://www.sunrise-tour.ru/russia/chernoe-more/tury/ standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type **id="main_content"**  specimen book. It has survived **class="main_block_of_content"** not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was populari **class="mboc_text"** sed in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum

Означает, что нужно получить со страницы https://www.sunrise-tour.ru/russia/chernoe-more/tury/ 
информацию из  блока 
```
<div id="main_content" >
    <div class="main_block_of_content" >
        <div class="mboc_text">
            Блок для парсинга
        </div>
    </div>
</div>
```
Теги не обязательно div, а могут быть любыми.
Если одноименных блоков встречается несколько, то нужно получить их все. 
Таких вставок (url + индетификаторы) в тексте может быть несколько.

**Далее**
 
В полученном тексте нужно вырезать html теги кроме строчных, заголовков, таблиц и параграфов, а также инлайновые стили.

Вывести полученный текст (или полученные тексты если было несколько) ниже на странице.


**Если будут вопросы - скайп alexandr_syrovatkin или email sa@startour.ru 
Удачи в реализации задания!**
