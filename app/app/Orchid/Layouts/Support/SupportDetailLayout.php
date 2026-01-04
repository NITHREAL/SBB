<?php

namespace App\Orchid\Layouts\Support;

use Domain\Support\Models\SupportMessage;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SupportDetailLayout extends  Table
{

    protected  $target = 'messages';

    protected function columns(): array
    {
        return [
            TD::make("id", "Автор сообщения")->render(function($model){
                return $this->messageAuthor($model);
            }),
            TD::make('text', 'Cообщение')->render(function ($model) {
                return '<div style="white-space: normal">' . $model->text . '</div>';
            }),
            TD::make('created_at', 'Отправлено')->render(function ($model){
                return $model->created_at;
            }),
            TD::make('updated_at', 'Прочтено')->render(function ($model){
                return $this->viewed($model);
            })
        ];
    }

    protected function viewed(SupportMessage $message): string
    {
        return $message->viewed
            ? $message->updated_at
            : '-';
    }

    protected function messageAuthor(SupportMessage $model): string
    {
        switch ($model->author){
            case "user":
                $class = "user-chat-message";
                $who = "Покупатель";
                break;
            case "administrator":
                $class = "admin-chat-message";
                $who = "Поддержка";
                break;
            default:
                $class = "default-chat-message";
                $who = "неизвестно";
        };
        if ($model->stuff_only){
            $class = "service-chat-message";
            $who = "Системное сообщение";
        }
        return "<div class=$class>$who</div>";
    }
}
