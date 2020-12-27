<?php

namespace App\Components\Widget;

use App\Admin\Controllers\AdminController;
use App\Models\Widget;
use App\Repositories\Widgets;
use Framework\Http\Message;
use Framework\Http\Request;
use PDOException;

class AdminWidgetController extends AdminController
{
    public function list(WidgetAdminTable $table)
    {
        $parsers = Widget::getWidgetParsers()->map(function ($parser) {
            return ['type' => $parser::getType(), 'name' => $parser::getName()];
        });

        return view('admin.widget.list', compact('table', 'parsers'));
    }

    public function create(Request $request)
    {
        $type = $request['type'];
        $form_view = "admin.widget.template.$type";
        $widget = new Widget();
        $action = route('admin.widget.do_create');
        return view('admin.widget.create', compact('type', 'form_view', 'widget', 'action'));
    }

    public function doCreate(Request $request, Widgets $repository)
    {
        try {
            $data = $request->only('name', 'uniqid', 'type', 'data');

            $widget = $repository->create($data);

            Message::success('Sikeres létrehozás');

            redirect_route('admin.widget.edit', $widget);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $uniqid = $request['uniqid'];
                $type = $request['type'];
                $form_view = "admin.widget.template.$type";
                Message::danger("Ez a kód ($uniqid) már foglalt");
                $widget = new Widget($request->all());
                return view('admin.widget.create', compact('type', 'form_view', 'widget'));
            }
        }
    }

    public function edit(Request $request, Widgets $repository)
    {
        $widget = $repository->find($request['id']);
        if (!$widget) {
            Message::danger('A keresett widget nem található!');
            redirect_route('admin.widget.list');
        }
        $type = $widget->type;
        $form_view = "admin.widget.template.$type";
        $action = route('admin.widget.update', $widget);
        return view('admin.widget.edit', compact('type', 'form_view', 'widget', 'action'));
    }

    public function update(Request $request, Widgets $repository)
    {
        try {
            $data = $request->only('name', 'uniqid', 'data');
            $widget = $repository->findOrFail($request['id']);
            $success = $repository->update($widget, $data);

            Message::success('Sikeres mentés');
            return redirect_route('admin.widget.edit', $widget);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $uniqid = $request['uniqid'];
                Message::danger("Ez a kód ($uniqid) már foglalt");
                return redirect_route('admin.widget.edit', $widget);
            }
        } catch (\Framework\Model\ModelNotFoundException $e) {
            Message::danger('Nem létező widget');
            redirect_route('admin.widget.list');
        }
    }

    public function delete(Request $request, Widgets $repository)
    {
        try {
            $repository->delete($repository->find($request['id']));
            Message::warning('Widget törölve.');
        } catch (\Exception $e) {
            Message::danger('váratlan hiba történt');
        } finally {
            redirect_route('admin.widget.list');
        }
    }

    public function generateUniqId(Request $request, Widgets $repository)
    {
        $name = $request['name'];
        $uniqid = $originalSlug = WidgetHelper::generateUniqId($name);

        while ($repository->getByUniqId($uniqid)) {
            preg_match('/([0-9]+)$/', $uniqid, $matches);

            $number = isset($matches[1]) ? $matches[1] + 1 : 1;

            $uniqid = "$originalSlug$number";
        }

        return ['success' => true, 'uniqid' => $uniqid];
    }
}
