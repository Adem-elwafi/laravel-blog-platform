// resources/js/mountComponents.js
import React from 'react';
import { createRoot } from 'react-dom/client';
import LikeButton from './components/features/LikeButton';
import Comments from './components/features/Comments';
import PostFilters from './components/features/PostFilters';
import InfiniteScrollPosts from './components/features/InfiniteScrollPosts';
import AddFriendButton from './components/features/AddFriendButton';
import FriendList from './components/features/FriendList';
import FriendRequests from './components/features/FriendRequests';
import PostComposer from './components/features/PostComposer';

const componentMap = {
  LikeButton,
  Comments,
  PostFilters,
  InfiniteScrollPosts,
  AddFriendButton,
  FriendList,
  FriendRequests,
  PostComposer,
};

function coerceValue(value) {
  try {
    return JSON.parse(value);
  } catch (err) {
    if (value === 'true') return true;
    if (value === 'false') return false;
    if (!Number.isNaN(Number(value)) && value.trim() !== '') return Number(value);
    return value;
  }
}

function parseProps(element) {
  const props = {};
  for (let attr of element.attributes) {
    if (!attr.name.startsWith('data-') || attr.name === 'data-mounted') continue;

    const propName = attr.name
      .substring(5)
      .replace(/-([a-z])/g, (match, letter) => letter.toUpperCase());

    props[propName] = coerceValue(attr.value);
  }
  return props;
}

export function mountComponents() {
  Object.keys(componentMap).forEach(componentName => {
    const elements = document.querySelectorAll(`[data-component="${componentName}"]`);
    elements.forEach(element => {
      if (element.dataset.mounted === 'true') return;

      const props = parseProps(element);
      const Component = componentMap[componentName];
      const root = createRoot(element);
      root.render(React.createElement(Component, props));
      element.dataset.mounted = 'true';
    });
  });
}

// Expose for dynamic mounting from React islands when needed
if (typeof window !== 'undefined') {
  window.mountComponents = mountComponents;
}