<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\CrudBack\OrderTrait;
use App\Http\Controllers\Traits\CrudBack\StoreTrait;
use App\Http\Controllers\Traits\CrudBack\UpdateTrait;
use App\Http\Controllers\Traits\CrudBack\DestroyTrait;

class CRUDBACKController extends Controller
{
    use OrderOrderTrait;
    use StoreTrait;
    use UpdateTrait;
    use DestroyTrait;
    
    public function inlineUpdate(Request $request, $section, $id = null)
        {
            // Pastikan balasan JSON
            if (!$request->expectsJson()) {
                $request->headers->set('Accept', 'application/json');
            }
            if ($section === 'about') {
                $about = AboutPage::first() ?? AboutPage::create([]);
                $data  = $request->only(['hero_title','hero_subtitle','title','description','signature']);

            if ($request->hasFile('image')) {
                    $request->validate(['image' => 'image|mimes:jpg,jpeg,png,webp|max:5120']);
                    if ($about->image && Storage::disk('public')->exists($about->image)) {
                        Storage::disk('public')->delete($about->image);
                    }
                    $data['image'] = $request->file('image')->store('homepage','public');
                    }

                    $about->update($data);

                    return response()->json([
                        'success'   => true,
                        'image_url' => $about->image ? asset('storage/'.$about->image) : null
                    ]);
            }
            if ($section === 'addon') {
                $addon = Addon::findOrFail($id);

                $validated = $request->validate([
                    'nama'      => 'sometimes|string|max:255',
                    'deskripsi' => 'sometimes|nullable|string',
                ]);

                if (array_key_exists('nama', $validated)) {
                    $addon->nama = $validated['nama'];
                }
                if (array_key_exists('deskripsi', $validated)) {
                    $addon->deskripsi = $validated['deskripsi'];
                }

                $addon->save();

                return response()->json(['success' => true]);
            }
            if ($section === 'aboutus') {
                $item = AboutUs::findOrFail($id);
                $fields = ['title', 'subtitle', 'description', 'content', 'model_type'];
                $data = $request->only($fields);
                if(isset($data['model_type'])){
                    $data['model_type'] = in_array($data['model_type'], ['model1','model2','model3'])
                        ? $data['model_type'] 
                        : $item->model_type;
                }
                if ($request->hasFile('images')) {
                    $request->validate([
                        'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
                    ]);

                    if (is_array($item->images)) {
                        foreach ($item->images as $oldImage) {
                            if (Storage::disk('public')->exists($oldImage)) {
                                Storage::disk('public')->delete($oldImage);
                            }
                        }
                    }
                    $uploadedImages = [];
                    foreach ($request->file('images') as $file) {
                        $uploadedImages[] = $file->store('aboutus', 'public');
                    }

                    $data['images'] = $uploadedImages;
                }

                elseif ($request->hasFile('image')) {
                    $request->validate(['image' => 'image|mimes:jpg,jpeg,png,webp|max:5120']);

                    if ($item->image && Storage::disk('public')->exists($item->image)) {
                        Storage::disk('public')->delete($item->image);
                    }

                    $data['image'] = $request->file('image')->store('aboutus', 'public');
                }

                if ($request->has('order')) {
                    $item->order = $this->cleanOrder($request->input('order'), AboutUs::class, $item->order);
                }

                if ($request->exists('active')) {
                    $item->active = $request->boolean('active');
                }

                $item->update($data);

                return response()->json([
                    'success'    => true,
                    'image_urls' => $item->all_image_urls, 
                    'order'      => $item->order,
                    'active'     => $item->active,
                    'model_type' => $item->model_type,
                ]);
            }

            $model = null;
            $fileField = 'image';
            switch ($section) {
                case 'slide':   $model = HeroSlide::findOrFail($id); break;
                case 'gallery': $model = GalleryItem::findOrFail($id); break;
                case 'hero':    $model = HeroContent::findOrFail($id); break;
                case 'service': $model = Service::findOrFail($id); break;
                case 'review':  $model = Review::findOrFail($id); $fileField = $request->hasFile('avatar') ? 'avatar' : 'image'; break;
                case 'faq':     $model = Faq::findOrFail($id); break;
                case 'social':  $model = Social::findOrFail($id); break;
                case 'promo':   $model = PromoBanner::findOrFail($id); break;
                case 'marquee': $model = Marquee::findOrFail($id); break;
                case 'GalleryItem': $model = GalleryItem::findOrFail($id); break;
                default:
                    return response()->json(['success'=>false,'message'=>'Unsupported inline update'], 400);
            }

            $fields = ['title','subtitle','description','icon','link','name','role','content','rating','date','question','answer','platform','handle','url','icon_class','category','text'];
            foreach ($fields as $f) {
                if ($request->has($f)) {
                    $model->{$f} = $f === 'rating' ? (int)$request->input($f) : $request->input($f);
                }
            }

            if ($request->has('order')) $model->order = $this->cleanOrder($request->input('order'), get_class($model), $model->order);
            if ($request->exists('active')) $model->active = $request->boolean('active');

            if ($request->hasFile('image') || $request->hasFile('avatar')) {
                $rule = ($section === 'slide')
                    ? [$fileField => 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:307200']
                    : [$fileField => 'image|mimes:jpg,jpeg,png,webp|max:5120'];

                $request->validate($rule);

                $column = ($section === 'review') ? 'avatar' : 'image';
                if (!$request->hasFile($column) && $request->hasFile('image') && $column === 'avatar') $column = 'avatar';
                if (!empty($model->{$column}) && Storage::disk('public')->exists($model->{$column})) {
                    Storage::disk('public')->delete($model->{$column});
                }

                $model->{$column} = $request->file($fileField)->store('homepage','public');
            }

            $model->save();

            return response()->json([
                'success'   => true,
                'image_url' => isset($model->image) && $model->image ? asset('public/storage/'.$model->image)
                            : (isset($model->avatar) && $model->avatar ? asset('public/storage/'.$model->avatar) : null),
            ]);
        }
}
